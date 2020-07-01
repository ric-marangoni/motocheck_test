/**
 *
 * @param value
 * @returns {boolean}
 */
export const isUndefined = function(value) {
  return typeof value === 'undefined';
};

/**
 *
 * @param val
 * @returns {boolean}
 */
export const isEmpty = function(val) {
  let has = Object.prototype.hasOwnProperty;

  let toString = Object.prototype.toString;

  // Null and Undefined...
  if (val == null) return true;

  // Booleans...
  if ('boolean' == typeof val) return false;

  // Numbers...
  if ('number' == typeof val) return val === 0;

  // Strings...
  if ('string' == typeof val) return val.length === 0;

  // Functions...
  if ('function' == typeof val) return val.length === 0;

  // Arrays...
  if (Array.isArray(val)) return val.length === 0;

  // Errors...
  if (val instanceof Error) return val.message === '';

  // Objects...
  if (val.toString == toString) {
    switch (val.toString()) {
      // Maps, Sets, Files and Errors...
      case '[object File]':
      case '[object Map]':
      case '[object Set]': {
        return val.size === 0;
      }

      // Plain objects...
      case '[object Object]': {
        for (let key in val) {
          if (has.call(val, key)) return false;
        }

        return true;
      }
    }
  }

  // Anything else...
  return false;
};

/**
 *
 * @param value
 * @returns {boolean}
 */
export const isJSON = function(value) {
  let re = /^\{[\s\S]*\}$|^\[[\s\S]*\]$/;

  if (typeof value !== 'string') {
    return false;
  }
  if (!re.test(value)) {
    return false;
  }
  try {
    JSON.parse(value);
  } catch (err) {
    return false;
  }

  return true;
};

/**
 *
 * @param string
 * @returns {*}
 */
export const capitalizeFirstLetter = function(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
};

/**
 *
 * @param array
 * @param value
 * @returns {boolean}
 */
export const isInArray = function(array, value) {
  array = array || [];
  let len = array.length;
  let i;
  for (i = 0; i < len; i++) {
    if (array[i] === value) {
      return true;
    }
  }
  return false;
};

/**
 *
 * @returns {Promise<string>}
 */
export const getLeroLero = async function() {
  return await (
    await fetch('http://whatthecommit.com/index.txt', {
      method: 'GET',
      headers: new Headers().append('Content-Type', 'text/plain'),
      mode: 'cors',
      cache: 'default',
    })
  ).text();
};

/**
 *
 * @param condition
 * @param fn
 */
export const isReady = function(condition, fn) {
  if (document.readyState == 'complete' && eval('typeof ' + condition) !== 'undefined' && condition) {
    fn();
  } else {
    setTimeout(function() {
      isReady(condition, fn);
    }, 100);
  }
};

/**
 *
 * @param arrayOrObjectToSort
 * @param term
 * @returns {*}
 */
export const sortAlphabeticalyByTerm = function(arrayOrObjectToSort, term) {
  return arrayOrObjectToSort.sort(function(a, b) {
    if (a.term < b.term) return -1;
    if (a.term > b.term) return 1;
    return 0;
  });
};

/**
 *
 * @param cname
 * @returns {string}
 */
export const getCookie = function(cname) {
  let name = cname ? cname + '=' : '';
  let decodedCookie = decodeURIComponent(window.parent.document.cookie);
  let ca = decodedCookie.split(';');
  let all = {};
  let hasAll = false;

  for (let i = 0; i < ca.length; i++) {
    let c = ca[i];

    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }

    if (name === '') {
      let indice = c.indexOf('=');
      all[c.substr(0, indice)] = c.substr(indice + 1);
      hasAll = true;
      continue;
    }

    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }

  return hasAll ? all : '';
};

/**
 *
 * @param cname
 * @param cvalue
 * @param exdays
 * @param cdomain
 * @returns {string}
 */
export const setCookie = function(cname, cvalue, exdays, cdomain) {
  if (typeof exdays === 'undefined') {
    exdays = new Date('Thu, 30 Sep 2027 00:00:01 GMT');
  } else if (typeof exdays === 'number') {
    let days = exdays,
      t = (exdays = new Date());
    t.setMilliseconds(t.getMilliseconds() + days * 864e5);
  }

  return (document.cookie = [encodeURIComponent(cname), '=', String(cvalue), exdays ? '; expires=' + exdays.toUTCString() : '', '; path=/', '; domain=' + cdomain || '.madeiramadeira.com.br'].join(
    ''
  ));
};

/**
 *
 * @param key
 * @returns {string}
 */
export const getItemFromSessionStorage = function(key) {
  return sessionStorage.getItem(key);
};

/**
 *
 * @param key
 */
export const removeItemFromSessionStorage = function(key) {
  return sessionStorage.removeItem(key);
};

/**
 *
 */
export const clearAllSessionStorage = function() {
  return sessionStorage.clear();
};

/**
 *
 * @param obj
 * @returns {*}
 */
export const memorySizeOf = function(obj) {
  let bytes = 0;

  function sizeOf(obj) {
    if (obj !== null && obj !== undefined) {
      switch (typeof obj) {
        case 'number':
          bytes += 8;
          break;
        case 'string':
          bytes += obj.length * 2;
          break;
        case 'boolean':
          bytes += 4;
          break;
        case 'object':
          let objClass = Object.prototype.toString.call(obj).slice(8, -1);
          if (objClass === 'Object' || objClass === 'Array') {
            for (let key in obj) {
              if (!obj.hasOwnProperty(key)) continue;
              sizeOf(obj[key]);
            }
          } else bytes += obj.toString().length * 2;
          break;
      }
    }
    return bytes;
  }

  function formatByteSize(bytes) {
    if (bytes < 1024) return bytes + ' bytes';
    else if (bytes < 1048576) return (bytes / 1024).toFixed(3) + ' KB';
    else if (bytes < 1073741824) return (bytes / 1048576).toFixed(3) + ' MB';
    else return (bytes / 1073741824).toFixed(3) + ' GB';
  }

  return formatByteSize(sizeOf(obj));
};

/**
 *
 * @returns {boolean}
 */
export const isMobile = function() {
  let isMobile = false;
  // device detection
  if (
    /(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|ipad|iris|kindle|Android|Silk|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(
      navigator.userAgent
    ) ||
    /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
      navigator.userAgent.substr(0, 4)
    )
  )
    isMobile = true;

  return isMobile;
};

/**
 *
 * @returns {*|boolean}
 */
export const isIPhone = function() {
  return /iphone|ipad/gi.test(navigator.userAgent);
};

/**
 *
 * @param el
 * @returns {boolean}
 */
export const isElementInViewport = function(el) {
  if (isEmpty(el)) return;

  try {
    //special bonus for those using jQuery
    if (typeof $ === 'function' && el instanceof $) {
      el = el[0];
    }

    if (typeof el.getBoundingClientRect === 'function') {
      let rect = el.getBoundingClientRect();

      return (
        (rect.top >= 0 || (rect.top < 0 && rect.height - rect.top >= 0)) &&
        rect.left >= 0 &&
        // !(rect.top == 0 && rect.right == 0 && rect.bottom == 0 && rect.left == 0 && rect.width == 0) && // Avoid itens on no pointview
        rect.top <= (window.innerHeight || document.documentElement.clientHeight)
      );
    }

    return;
  } catch (e) {
    return;
  }
};

/**
 *
 * @param CPF
 * @returns {boolean}
 */
export const isValidCPF = function(CPF) {
  let sum = 0;
  let _module;

  if (CPF == '00000000000') return false;

  for (let i = 1; i <= 9; i++) sum = sum + parseInt(CPF.substring(i - 1, i)) * (11 - i);
  _module = (sum * 10) % 11;

  if (_module == 10 || _module == 11) _module = 0;
  if (_module != parseInt(CPF.substring(9, 10))) return false;

  sum = 0;
  for (let i = 1; i <= 10; i++) sum = sum + parseInt(CPF.substring(i - 1, i)) * (12 - i);
  _module = (sum * 10) % 11;

  if (_module == 10 || _module == 11) _module = 0;
  if (_module != parseInt(CPF.substring(10, 11))) return false;
  return true;
};

/**
 *
 * @returns {{user_navigator_version: number | *, user_navigator_vendor: string, user_navigator_app_version: string, user_navigator_platform: string, user_navigator_user_agent: string, user_os_name: *, user_navigator_name: *, user_os_version: number | *}}
 */
export const getOsAndBrowserData = function() {
  let module = {
    options: [],
    header: [navigator.platform, navigator.userAgent, navigator.appVersion, navigator.vendor, window.opera],
    dataos: [
      {
        name: 'Windows Phone',
        value: 'Windows Phone',
        version: 'OS',
      },
      {
        name: 'Windows',
        value: 'Win',
        version: 'NT',
      },
      {
        name: 'iPhone',
        value: 'iPhone',
        version: 'OS',
      },
      {
        name: 'iPad',
        value: 'iPad',
        version: 'OS',
      },
      {
        name: 'Kindle',
        value: 'Silk',
        version: 'Silk',
      },
      {
        name: 'Android',
        value: 'Android',
        version: 'Android',
      },
      {
        name: 'PlayBook',
        value: 'PlayBook',
        version: 'OS',
      },
      {
        name: 'BlackBerry',
        value: 'BlackBerry',
        version: '/',
      },
      {
        name: 'Macintosh',
        value: 'Mac',
        version: 'OS X',
      },
      {
        name: 'Linux',
        value: 'Linux',
        version: 'rv',
      },
      {
        name: 'Palm',
        value: 'Palm',
        version: 'PalmOS',
      },
    ],
    databrowser: [
      {
        name: 'Chrome',
        value: 'Chrome',
        version: 'Chrome',
      },
      {
        name: 'Firefox',
        value: 'Firefox',
        version: 'Firefox',
      },
      {
        name: 'Safari',
        value: 'Safari',
        version: 'Version',
      },
      {
        name: 'Internet Explorer',
        value: 'MSIE',
        version: 'MSIE',
      },
      {
        name: 'Opera',
        value: 'Opera',
        version: 'Opera',
      },
      {
        name: 'BlackBerry',
        value: 'CLDC',
        version: 'CLDC',
      },
      {
        name: 'Mozilla',
        value: 'Mozilla',
        version: 'Mozilla',
      },
    ],
    init: function() {
      let agent = this.header.join(' '),
        os = this.matchItem(agent, this.dataos),
        browser = this.matchItem(agent, this.databrowser);

      return {
        os: os,
        browser: browser,
      };
    },
    matchItem: function(string, data) {
      let i = 0,
        j = 0,
        html = '',
        regex,
        regexv,
        match,
        matches,
        version;

      for (i = 0; i < data.length; i += 1) {
        regex = new RegExp(data[i].value, 'i');
        match = regex.test(string);
        if (match) {
          regexv = new RegExp(data[i].version + '[- /:;]([\\d._]+)', 'i');
          matches = string.match(regexv);
          version = '';
          if (matches) {
            if (matches[1]) {
              matches = matches[1];
            }
          }
          if (matches) {
            matches = matches.split(/[._]+/);
            for (j = 0; j < matches.length; j += 1) {
              if (j === 0) {
                version += matches[j] + '.';
              } else {
                version += matches[j];
              }
            }
          } else {
            version = '0';
          }
          return {
            name: data[i].name,
            version: parseFloat(version),
          };
        }
      }
      return {
        name: 'unknown',
        version: 0,
      };
    },
  };

  let e = module.init();
  let obj = {
    user_os_name: e.os.name,
    user_os_version: e.os.version,
    user_navigator_name: e.browser.name,
    user_navigator_version: e.browser.version,
    user_navigator_user_agent: navigator.userAgent,
    user_navigator_app_version: navigator.appVersion,
    user_navigator_platform: navigator.platform,
    user_navigator_vendor: navigator.vendor,
  };

  return obj;
};

/**
 *
 * @param selector
 * @returns {Element}
 */
export const queryBySelector = function(selector) {
  let element = document.querySelector(selector);

  if (!element) {
    throw Error('Function getBySelector: HTML ELEMENT WITH SELECTOR:' + selector + ' WAS NOT FOUND!');
  }

  return element;
};

export const queryAllBySelector = function(selector) {
  let elements = document.querySelectorAll(selector);

  if (!elements) {
    throw Error('Function queryAllBySelector: HTML ELEMENTS WITH SELECTOR:' + selector + ' WERE NOT FOUND!');
  }

  return Array.prototype.slice.call(elements);
};

export const debounce = function(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this,
      args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};

export const findAndReplaceUrls = function(text) {
  return (text || '').replace(
    /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)(:[0-9]*)?((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/gi,
    function(ocur) {
      let link = ocur;
      if (!/^(http:\/\/|https:\/\/)/.test(link.toLowerCase())) link = '//' + link;
      return '<a href="javascript:false;" onclick="window.open(\'' + link + '\')">' + ocur + '</a>';
    }
  );
};

/**
 *
 * @param {string} text
 */
export function onlyNumbers(text) {
  const numberPattern = /\d+/g;

  return text.match(numberPattern)[0];
}
