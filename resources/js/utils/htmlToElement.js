/**
 * @param {String} html representing a single element
 * @link https://stackoverflow.com/questions/494143/creating-a-new-dom-element-from-an-html-string-using-built-in-dom-methods-or-pro/35385518#35385518
 * @return {HTMLElement}
 */
export function htmlToElement(html) {
  const template = document.createElement('template');
  html = html.trim();

  template.innerHTML = html;

  const element = template.content.firstChild;

  return element;
}
