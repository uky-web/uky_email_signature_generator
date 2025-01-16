(function ($, Drupal) {
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

  $('*[data-action="copy-element"]').click(function(e) {
    const elem = $(this);
    const htmlTarget = $(this).data('target');
    const textTarget = $(this).data('target-text') || null;

    // Copy element(s) and update button text
    copyToClipboard($(htmlTarget).html(), $(textTarget).text() || null).then(function(result) {
      elem.text('Copied!');
    }, function(err) {
      elem.text('Copy failed');
    });
  });
})(jQuery, Drupal);
