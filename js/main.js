(function ($, Drupal) {
  function copyToClipboard(html) {
    const el = document.createElement('div');
    el.innerHTML = html;
    const plain = el.textContent || html;

    const content = el.innerHTML;
    const blob = new Blob([content], {type: 'text/html'});
    const text = new Blob([plain], {type: 'text/plain'});
    const clipboardItem = new window.ClipboardItem({
      'text/html': blob ,
      'text/plain': text
    });
    return navigator.clipboard.write([clipboardItem]);
  }

  $('*[data-action="copy-element"]').click(function(e) {
    const elem = $(this);
    const target = $(this).data('target');
    copyToClipboard($(target).html()).then(function(result) {
      elem.text('Copied!');
    }, function(err) {
      elem.text('Copy to clipboard failed');
    });
  });
})(jQuery, Drupal);
