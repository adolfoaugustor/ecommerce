let xhrPost = (url, data, success, error, complete) => {
  let postParams = Object.keys(data)
    .map(key => key+'='+encodeURIComponent(data[key]))
    .join('&');
  let xhr = new XMLHttpRequest();
  xhr.open('POST', url);
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  xhr.onload = function() {
    if (xhr.status === 200) {
      success(JSON.parse(xhr.responseText));
    }
    else {
      error(xhr);
    }
    complete();
  };
  xhr.send(postParams);
};

jQuery.validator.addMethod("dateBR", function(value, element) {
  return this.optional(element) || moment(value,"DD/MM/YYYY").isValid();
}, "Please enter a valid date in the format DD/MM/YYYY");

jQuery(document).ready(() => {

  let $freteCalculateForm = jQuery('#freteCalculateForm');
  let $productCreateForm = jQuery('#productCreateForm');
  let $productRemove = jQuery('.productRemove');
  let $modalBookNow = jQuery('#modalBookNow');
  let $btnBookNow = jQuery('.btnBookNow');
  let $addPromotionForm = jQuery('#addPromotionForm');

  jQuery('[name="price"]').mask('999999,90', { reverse: true });
  let decimalSelectors = ['[name="weight"]', '[name="height"]', '[name="width"]', '[name="length"]', '[name="diameter"]'];
  jQuery(decimalSelectors.join(', ')).mask('999990');
  jQuery('[name="cep"]').mask('00000-000');

  $('[name="start_date"], [name="end_date"]').mask('00/00/0000');

  if ($freteCalculateForm.length) {

    $freteCalculateForm.validate({
      rules: {
        cep: 'required',
      },
      submitHandler: (form) => {

        let $btnSubmit = $freteCalculateForm.find('[type="submit"]');
        let cepValue = $freteCalculateForm.find('[name="cep"]').val();
        let productSlugValue = $freteCalculateForm.find('[name="product_slug"]').val();

        let smallMessage = $freteCalculateForm.find('small.message');

        $btnSubmit.text('loading...');
        $btnSubmit.attr('disabled', true);

        smallMessage.text('');

        xhrPost(
          '/frete/calculate/20081902/'+cepValue.replace('-', ''),
          {
            slug: productSlugValue
          },
          data => {
            if (data.code === 200) {
              smallMessage.css('color', 'black');
            } else {
              smallMessage.css('color', 'red');
            }
            smallMessage.text(data.message);
          },
          data => { console.warn(data) },
          () => {
            $btnSubmit.text('Calculate');
            $btnSubmit.removeAttr('disabled');
          }
        )
      }
    });
  }

  if ($productCreateForm.length) {

    $productCreateForm.validate({
      rules: {
        name: 'required',
        price: 'required',
        image: 'required',
        weight: 'required',
        height: 'required',
        width: 'required',
        length: 'required',
        diameter: 'required',
        description: 'required',
      },
      submitHandler: form => {
        form.submit();
      }
    })
  }

  if ($productRemove.length) {

    $productRemove.on('click', evt => {
      if (!confirm('Would you like to delete this record?')) {
        evt.preventDefault();
      }
    });
  }

  if ($modalBookNow.length) {

    $btnBookNow.on('click', evt => {
      evt.preventDefault();
      let cep = $freteCalculateForm.find('[name="cep"]').val().replace('-', '');
      $modalBookNow.modal(cep.length === 8 ? 'show' : 'hide');
      if (cep.length !== 8) {
        alert('Preencha corretamente o campo CEP');
        $freteCalculateForm.find('[name="cep"]').focus();
      } else {
        setTimeout(() => {
          $modalBookNow.find('[name="name"]').focus();
        }, 500);
      }
    });

    $modalBookNow.find('.submit').on('click', evt => {
      evt.preventDefault();
      let slug = $freteCalculateForm.find('[name="product_slug"]').val();
      let params = {
        name: $modalBookNow.find('[name="name"]').val(),
        email: $modalBookNow.find('[name="email"]').val(),
        cep: $freteCalculateForm.find('[name="cep"]').val().replace('-', ''),
      };
      if (params.name && params.email && params.cep) {
        jQuery(evt.currentTarget).text('Loading...').attr('disabled', true);
        $modalBookNow.find('[data-dismiss="modal"]').attr('disabled', true);
        jQuery.post('/products/%1/book-now'.replace('%1', slug), params, data => {
          alert(data.message);
          $modalBookNow.modal('hide');
        }).fail(data => {
          console.warn(data);
        }).always(() => {
          jQuery(evt.currentTarget).text('Book Now').removeAttr('disabled');
          $modalBookNow.find('[data-dismiss="modal"]').removeAttr('disabled');
        })

      } else {

        alert('Preencha todos os campos');
      }
    });
  }

  if ($addPromotionForm.length) {

    $addPromotionForm.validate({
      rules: {
        start_date: {
          required: true,
          dateBR: true,
        },
        end_date: {
          required: true,
          dateBR: true,
        },
        price: 'required',
      },
      submitHandler: form => {
        form.submit();
      }
    })
  }
});
