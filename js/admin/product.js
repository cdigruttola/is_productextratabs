/**
 * Copyright since 2007 Carmine Di Gruttola
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    cdigruttola <c.digruttola@hotmail.it>
 *  @copyright Copyright since 2007 Carmine Di Gruttola
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *
 */

import TranslatableInput from '@PSJs/components/translatable-input';

const {$} = window

$(() => {
  window.prestashop.component.initComponents(
    [
      "TinyMCEEditor",
      'TranslatableField',
      'TranslatableInput',
    ],
  );

  new TranslatableInput();

  const text = "[Some text] ][with[ [some important info]";
  console.log(text.match(/(?<=\[)[^\][]*(?=])/g));

  $('.save_extra_tab').click(function (e) {

    let id_tab = $(this).attr('data-content');
    let inputs = $('#module_is_productextratabs .card-text').filter(function () {
      return $(this).attr('data-content') === id_tab
    }).children().children();

    let values = [];
    inputs.each(function () {
      if ($(this).is('input')) {
        values.push({key: $(this)[0].name.match(/(?<=\[)[^\][]*(?=])/g), value: $(this).val()});
      }
      if ($(this).is('.switch-widget')) {
        let input = $(this).find('input:checked');
        values.push({key: input[0].name.match(/(?<=\[)[^\][]*(?=])/g), value: input.val()});
      }
      let text = $(this).find(':text');
      text.each(function () {
        values.push({key: $(this)[0].name.match(/(?<=\[)[^\][]*(?=])/g), value: $(this)[0].value});
      });
      let textarea = $(this).find('textarea');
      textarea.each(function () {
        values.push({key: $(this)[0].name.match(/(?<=\[)[^\][]*(?=])/g), value: $(this)[0].value});
      });
    });

    $.ajax({
      type: 'POST',
      url: $('.save_extra_tab').attr('data-action'),
      async: false,
      dataType: "json",
      data: {
        values: values
      },
      headers: {Accept: "application/json"},
      success: function (result) {
        alert(result.message)
      },
      error: function (result) {
        alert(result.responseJSON.message)
      }
    });
  });
});
