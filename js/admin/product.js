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

  $('.save_extra_tab').click(function (e) {

    let id_tab = $(this).attr('data-content');
    let languages = $(this).attr('data-languages').split(',');

    let inputs = $('#module_is_productextratabs .card-text').filter(function () {
      return $(this).attr('data-content') === id_tab
    }).children();

    let values = [];

    const baseIdName = 'product_extra_tab_product_';
    let token = inputs.find('#' + baseIdName + '_token').val();
    let name = inputs.find('#' + baseIdName + 'name_' + id_tab).val();
    let active = inputs.find('#' + baseIdName + 'active_' + id_tab + ' input:checked').val();

    let titles = [];
    let contents = [];
    languages.forEach(language => {
      titles.push({
        languageId: language,
        value: inputs.find('#' + baseIdName + 'title_' + id_tab + '_' + language).val()
      });
      contents.push({
        languageId: language,
        value: inputs.find('#' + baseIdName + 'content_' + id_tab + '_' + language)[0].value
      });
    });

    $.ajax({
      type: 'POST',
      url: $(this).attr('data-action'),
      async: false,
      dataType: "json",
      data: {
        token: token,
        name: name,
        active: active,
        titles: titles,
        contents: contents,
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
