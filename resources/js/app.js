require('alpinejs');

import Litepicker from 'litepicker';

window.daterange = (element) => {
  new Litepicker({
    element,
    delimiter: ' ~ ',
    singleMode: false,
    format: 'DD.MM.YYYY',
    maxDate: new Date,
    onSelect() {
      element.form.submit();
    }
  });
}
