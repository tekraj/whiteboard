const dotEnv = require(`dotenv`);
const dotenvExpand = require(`dotenv-expand`);
// const mongoose = require(`mongoose`); // keeps logs to mongoose!
const cors = require(`cors`);

const envSetup = () => {
  // Load environment variables
  const envConfig = dotEnv.config({
    path: `.env`,
    debug: process.env.DEBUG,
  });
  dotenvExpand(envConfig);

  if (envConfig.error) {
    throw envConfig.error;
  }
};
// const mongoDbSetup = (consoleError = false, throwError = false) => {
//   mongoose
//     .connect(process.env.MONGO_CONNECTION_STRING, {
//       useNewUrlParser: true,
//       useUnifiedTopology: true,
//       useFindAndModify: false,
//       useCreateIndex: true,
//       connectTimeoutMS: 1000,
//     })
//     .then(() => {
//       console.info(`******************************`);
//       console.info(`Database Connected!`);
//       console.info(`******************************`);
//     })
//     .catch(err => {
//       console.error(`******************************`);
//       console.error(`Database Connection Error!\n`, consoleError ? err : ``);
//       console.error(`******************************`);
//       if (throwError) throw err;
//     });
// };
const corsSetup = (whitelistDomains: [String] | String, includeFromEnv = true) => {
  /**
   * @param whitelistDomains is a list of domains to allow cors requests from
   * Can be an array of domains
   * Or a string with domains separated by a comma
   * @param includeFromEnv will look for CORS_WHITELIST in environment. Defaults to true
   */
  let whitelist: Array<String | undefined> = [];

  if (Array.isArray(whitelistDomains)) {
    whitelist = [...whitelist, ...whitelistDomains];
  } else if (typeof whitelistDomains === `string` && whitelistDomains.length) {
    const whitelistFromParam = whitelistDomains.split(`,`).map(x => x.trim());
    whitelist = [...whitelist, ...whitelistFromParam];
  }
  if (includeFromEnv) {
    const whitelistFromEnv: Array<string | undefined> =
      includeFromEnv &&
      process.env.CORS_WHITELIST &&
      process.env.CORS_WHITELIST.split(`,`).map(x => x.trim()) || [];

    whitelist = [...whitelist, ...whitelistFromEnv];
    // required if you want to hit api through browser, origin is undefined!
    if (process.env.CORS_SELF) {
      whitelist.push(undefined);
    }
  }
  const corsOptions = {
    origin: (origin: string, callback: Function) => {
      console.log("origin", origin);

      if (whitelist && whitelist.indexOf(origin) !== -1) {
        callback(null, true);
      } else {
        callback(new Error(`Blocked by CORS`));
      }
    },
  };
  return cors(corsOptions);
};
module.exports = {
  envSetup,
  // dbSetup: mongoDbSetup,
  corsSetup,
};
