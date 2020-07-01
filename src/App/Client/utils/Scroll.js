export const ScrollInfo = function(element) {
  console.log('offsetHeight', element.offsetHeight);
  console.log('clientHeight', element.clientHeight);
  console.log('scrollHeight', element.scrollHeight);
  console.log('scrollTop', Math.round(element.scrollTop));
};

export const currentScroll = function(element) {
  return element.scrollTop;
};

export const isScrollAtBottom = function(element) {
  return Math.round(element.scrollTop + element.offsetHeight) === element.scrollHeight;
};

export const isScrollNearTheBottom = function(element, offset) {
  let currentScroll = Math.round(element.scrollTop + element.offsetHeight);
  let bottomNearNess = Math.round(element.scrollHeight - offset);

  return currentScroll >= bottomNearNess;
};

export const isScrollAtTop = function(element) {
  let hasVerticalScrollbar = element.scrollHeight > element.clientHeight;
  return element.scrollTop === 0 && hasVerticalScrollbar;
};

export const ScrollToBottom = function(element) {
  setTimeout(() => {
    element.scrollTop = element.scrollHeight + element.offsetHeight;
  }, 175);
};

export const ScrollToTop = function(element) {
  element.scrollTop = 0;
};

export const ScrollTo = function(element, value) {
  element.scrollTop = value;
};

export const needsScroll = function(element) {
  return !(element.scrollTop === 0 && Math.abs(element.scrollHeight - element.offsetHeight) < 175);
};
