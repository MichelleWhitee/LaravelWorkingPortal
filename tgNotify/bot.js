const { Telegraf } = require('telegraf');
const mysql = require('mysql2');
const bcrypt = require('bcrypt');
require('dotenv').config();

const bot = new Telegraf(process.env.TELEGRAM_BOT_TOKEN);

// Создаем пул подключений
const pool = mysql.createPool({
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});

// Проверка подключения к базе
pool.getConnection((err, connection) => {
  if (err) {
    console.error('Ошибка подключения к БД:', err.message);
    return;
  }
  console.log('Успешно подключено к БД');
  connection.release();
});

// Middleware для проверки авторизации
async function isAuthorized(ctx, next) {
  const chatId = ctx.chat.id;

  pool.query('SELECT * FROM users WHERE chat_id = ?', [chatId], (err, results) => {
    if (err) {
      console.error('Ошибка выполнения запроса:', err.message);
      ctx.reply('Произошла ошибка при проверке авторизации.');
      return;
    }

    if (results.length === 0) {
      ctx.reply('❗ Вы не авторизованы. Пожалуйста, используйте /login для авторизации.');
      return;
    }

    next();
  });
}

// Команда /start
bot.start((ctx) => {
  ctx.replyWithMarkdownV2(
    `💡 *Привет, ${escapeMarkdownV2(ctx.from.first_name)}\\!*\nЯ твой Telegram\\-бот для управления задачами\\.\n\n` +
    `🌟 Используйте команду \`/login <email> <password>\` для авторизации\\.`,
    {
      reply_markup: {
        inline_keyboard: [
          [{ text: "Войти", callback_data: "login" }],
          [{ text: "Помощь", callback_data: "help" }],
        ],
      },
    }
  );
});


// Команда /login
bot.command('login', (ctx) => {
  const args = ctx.message.text.split(' ').slice(1);
  if (args.length !== 2) {
    ctx.reply('🚨 Неверный формат команды. Используйте: /login <email> <password>');
    return;
  }

  const [email, password] = args.map(arg => arg.trim());  // Убираем пробелы


  pool.query(
    'SELECT * FROM users WHERE email = ?',
    [email],
    (err, results) => {
      if (err) {
        console.error('Ошибка выполнения запроса:', err.message);
        ctx.reply('⚠️ Произошла ошибка при выполнении запроса.');
        return;
      }

      if (results.length === 0) {
        ctx.reply('❗ Пользователь с таким email не найден.');
        return;
      }

      const user = results[0];

      const hashPass = /^\$2y\$/.test(user.password) ? '$2a$' + user.password.slice(4) : user.password;

      bcrypt.compare(password, hashPass, (compareErr, isMatch) => {
        if (compareErr) {
          console.error('Ошибка сравнения паролей:', compareErr.message);
          ctx.replyWithMarkdownV2(
            '❌ *Ошибка авторизации\\!*\n' +
            'Пожалуйста, проверьте пароль и попробуйте снова\\.'
          );
          
          return;
        }

        if (isMatch) {
          pool.query(
            'UPDATE users SET chat_id = ? WHERE id = ?',
            [ctx.from.id, user.id],
            (updateErr) => {
              if (updateErr) {
                console.error('Ошибка обновления chat_id:', updateErr.message);
                ctx.replyWithMarkdownV2(
                  `❌ *Ошибка обновления chat_id*\n` +
                  `Не удалось подключить уведомления\\.`
                );
                
                return;
              }

              ctx.replyWithMarkdownV2(
                `🎉 *Добро пожаловать, ${escapeMarkdownV2(user.name)}\\!*\n` +
                `✅ Вы успешно авторизовались\\.`
              );
              
            }
          );
        } else {
          ctx.reply(`❌ Неверный пароль. Попробуйте снова.`);
        }
      });
    }
  );
});


// Команда /stop
bot.command('stop', isAuthorized, (ctx) => {
  const chatId = ctx.from.id;

  pool.query('UPDATE users SET chat_id = 0 WHERE chat_id = ?', [chatId], (err, results) => {
    if (err) {
      console.error('Ошибка выполнения запроса:', err.message);
      ctx.reply('⚠️ Произошла ошибка при очистке chat_id.');
      return;
    }

    if (results.affectedRows > 0) {
      ctx.replyWithMarkdownV2(
        `🔒 *Вы вышли из системы\\.*\n` +
        `Уведомления отключены\\.`,
        {
          reply_markup: {
            inline_keyboard: [
              [{ text: "Войти снова", callback_data: "login" }],
            ],
          },
        }
      );
      
    } else {
      ctx.reply('🚫 Ваш chat_id уже очищен или не найден.');
    }
  });
});

// Пример защищенной команды /get_admin
bot.command('get_admin', isAuthorized, (ctx) => {
  pool.query('SELECT name FROM users WHERE adm = 1', (err, results) => {
    if (err) {
      console.error('Ошибка выполнения запроса:', err.message);
      ctx.reply('⚠️ Произошла ошибка при выполнении запроса.');
      return;
    }
    if (results.length > 0) {
      ctx.reply(`👑 Администратор: ${results[0].name}`);
    } else {
      ctx.reply('❗ Администратор не найден.');
    }
  });
});


bot.action("login", (ctx) => {
  ctx.reply("Введите ваш email и пароль с помощью команды /login <email> <password>");
});

bot.action("help", (ctx) => {
  ctx.replyWithMarkdownV2(
    `💡 *Список доступных команд:*\n` +
    `\\- \`/login <email> <password>\` \— Авторизация\n` +
    `\\- \`/stop\` \— Выйти из системы\n` +
    `\\- \`/get_admin\` \— Получить данные администратора`
  );ctx.update.message = {
    text: "/stop",
    from: ctx.from,
    chat: ctx.chat,
  };

  bot.handleUpdate(ctx.update);
});



function escapeMarkdownV2(text) {
  return text.replace(/([_*[\]()~`>#+\-=|{}.!])/g, '\\$1');
}




// Проверка успешного запуска
(async () => {
  try {
    const botInfo = await bot.telegram.getMe();
    console.log(`Бот успешно запущен! Имя: ${botInfo.first_name}, Username: @${botInfo.username}`);

    // Запуск бота
    bot.launch();
  } catch (error) {
    console.error('Ошибка запуска бота:', error.message);
  }
})();
