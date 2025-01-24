(function ($, Drupal) {
  const config = {
    button: {
      successText: 'Copied!',
      failureText: 'Copy failed',
      textTimeout: 5000
    }
  };

  function copyToClipboard(html, text = null) {
    // Generate an element with the HTML content
    const el = document.createElement('div');
    el.innerHTML = html;
    // Set text content if provided, otherwise infer/generate from HTML
    const plain = text || el.textContent || html;
    const htmlBlob = new Blob([el.innerHTML], {type: 'text/html'});
    const textBlob = new Blob([plain], {type: 'text/plain'});

    // Create clipboard item and write to clipboard
    const clipboardItem = new window.ClipboardItem({
      'text/html': htmlBlob ,
      'text/plain': textBlob
    });
    return navigator.clipboard.write([clipboardItem]);
  }

  /**
   * Set an element's text content and revert after some time
   *
   * @param {object} elem - jQuery element to affect
   * @param {string} text - The text to set the element to
   * @param {int} timeout - Time to wait before reverting the text in ms
   * @private
   */
  function __setElementText(elem, text, timeout) {
    let origText = elem.text();

    elem.text(text);
    setTimeout(function() {
      elem.text(origText);
    }, timeout);
  }

  $('*[data-action="copy-element"]').click(function(e) {
    const elem = $(this);
    const htmlTarget = $(this).data('target');
    const textTarget = $(this).data('target-text') || null;

    // Copy element(s) and update button text
    copyToClipboard($(htmlTarget).html(), $(textTarget).text() || null).then(function() {
      __setElementText(elem, config.button.successText, config.button.textTimeout);
    }, function(err) {
      __setElementText(elem, config.button.failureText, config.button.textTimeout);
    });
  });

  $('*[data-action="download-element"]').click(function(e) {
    const target = $(this).data('target');
    // Create element and initiate download
    let el = document.createElement('a');
    el.download = 'signature.html';
    el.href = 'data:text/html;charset=utf-8,' + encodeURIComponent($(target).html());
    el.click();
  });
})(jQuery, Drupal);
