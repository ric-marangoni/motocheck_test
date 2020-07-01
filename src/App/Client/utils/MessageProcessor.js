let MessageProcessor = (function() {
  function findAndReplaceUrls(text) {
    return (text || '').replace(
      /((([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)(:[0-9]*)?((?:\/[\+~%\/\.\w\-_]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?)/gi,
      function(ocur) {
        let link = ocur;
        if (!/^(http:\/\/|https:\/\/)/.test(link.toLowerCase())) link = '//' + link;
        return `<a href="${link}" target="_blank">${ocur}</a>`;
      }
    );
  }

  function stripHtml(html) {
    let content,
      temporalDivElement = document.createElement('div');
    temporalDivElement.innerHTML = html;
    content = temporalDivElement.textContent || temporalDivElement.innerText || '';
    return content;
  }

  return {
    process: findAndReplaceUrls,
    clean: stripHtml,
  };
})();

export default MessageProcessor;
