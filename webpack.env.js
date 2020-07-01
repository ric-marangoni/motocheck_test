const APP_DEVELOPMENT = "'//172.17.0.1:8881'";
const APP_STAGING = "'//172.17.0.1:8881'";
const APP_PRODUCTION = "'//172.17.0.1:8881'";

module.exports = function(env) {
  const data = {};

  switch (env) {
    case 'development': {
      data.appBaseUrl = APP_DEVELOPMENT;
      break;
    }
    case 'staging': {
      data.appBaseUrl = APP_STAGING;
      break;
    }
    case 'production': {
      data.appBaseUrl = APP_PRODUCTION;
      break;
    }
  }

  return data;
};
