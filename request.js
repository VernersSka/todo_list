const request = {
  /**
   * 1) Apstādina submit notikumu
   * 2) Nosūta formas datus uz adresi aprakstītu atributā "action"
   * 3) Saņemot atbildi no servera izsauc funkciju callback, ja tāda ir padota
   *
   * @param {*} event
   * @param {*} form
   * @param {function || false} callback() - function parameters (responseText, form)
   */
  post: function (event, form, callback = false) {
    event.preventDefault();
    let url = form.getAttribute('action'),
      data = new FormData(form);

    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      if (callback !== false) {
        callback(this.responseText, form);
      }
    };
    xhttp.open('POST', url);

    xhttp.send(data);
  },

  get: function (url, callback = false) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function () {
      let response_object = JSON.parse(this.responseText);
      if (response_object.status == true) {
        if (callback) {
          callback(response_object);
        }
      }
    };
    xhttp.open('GET', url);
    xhttp.send();
  },
};
