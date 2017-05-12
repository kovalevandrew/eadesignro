/**
 * EaDesgin
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eadesign.ro so we can send you a copy immediately.
 *
 * @category    eadesigndev_pdfgenerator
 * @copyright   Copyright (c) 2008-2016 EaDesign by Eco Active S.R.L.
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

define([
    'jquery',
    'mage/translate',
    'Magento_Ui/js/modal/modal',
    'jquery/ui',
    'prototype',
], function (jQuery, $t) {

    window.EadesigVariables = {
        textareaElementId: null,
        variablesContent: null,
        dialogWindow: null,
        dialogWindowId: 'variables-chooser',
        overlayShowEffectOptions: null,
        overlayHideEffectOptions: null,
        insertFunction: 'EadesigVariables.insertVariable',
        init: function (textareaElementId, insertFunction) {
            if ($(textareaElementId)) {
                this.textareaElementId = textareaElementId;
            }
            if (insertFunction) {
                this.insertFunction = insertFunction;
            }
        },

        resetData: function () {
            this.variablesContent = null;
            this.dialogWindow = null;
        },

        openVariableChooser: function (variables) {
            if (this.variablesContent == null && variables) {
                this.variablesContent = '<ul class="insert-variable">';
                variables.each(function (variableGroup) {
                    if (variableGroup.label && variableGroup.value) {
                        this.variablesContent += '<li><b>' + variableGroup.label + '</b></li>';
                        (variableGroup.value).each(function (variable) {
                            if (variable.value && variable.label) {
                                this.variablesContent += '<li>' +
                                    this.prepareVariableRow(variable.value, variable.label) + '</li>';
                            }
                        }.bind(this));
                    }
                }.bind(this));
                this.variablesContent += '</ul>';
            }
            if (this.variablesContent) {
                this.openDialogWindow(this.variablesContent);
            }
            this.resetData();
        },
        openDialogWindow: function (variablesContent) {
            var windowId = this.dialogWindowId;
            jQuery('<div id="' + windowId + '">' + EadesigVariables.variablesContent + '</div>').modal({
                title: $t('Insert Variable...'),
                type: 'slide',
                buttons: [],
                closed: function (e, modal) {
                    modal.modal.remove();
                }
            });

            jQuery('#' + windowId).modal('openModal');

            variablesContent.evalScripts.bind(variablesContent).defer();
        },
        closeDialogWindow: function () {
            jQuery('#' + this.dialogWindowId).modal('closeModal');
        },
        prepareVariableRow: function (varValue, varLabel) {
            var value = (varValue).replace(/"/g, '&quot;').replace(/'/g, '\\&#39;');
            var content = '<a href="#" onclick="' + this.insertFunction + '(\'' + value + '\');return false;">' + varLabel + '</a>';
            return content;
        },
        insertVariable: function (value) {
            var windowId = this.dialogWindowId;
            jQuery('#' + windowId).modal('closeModal');
            var textareaElm = $(this.textareaElementId);
            if (textareaElm) {
                var scrollPos = textareaElm.scrollTop;
                updateElementAtCursor(textareaElm, value);
                textareaElm.focus();
                textareaElm.scrollTop = scrollPos;
                jQuery(textareaElm).change();
                textareaElm = null;
            }
            return;
        }
    };

    window.EadesignVariablePlugin = {
        editor: null,
        variables: null,
        textareaId: null,
        setEditor: function (editor) {
            this.editor = editor;
        },
        loadChooser: function (url, textareaId) {

            var fieldVal = jQuery('input[name=variables_entity_id]').val();
            if(fieldVal == 0 || !fieldVal){
                alert($t('Please select a valid invoice...'));
                return;
            }

            this.textareaId = textareaId;
            new Ajax.Request(url, {
                parameters: {'variables_entity_id':fieldVal},
                onComplete: function (transport) {
                    if (transport.responseText.isJSON()) {
                        EadesigVariables.init(null, 'EadesignVariablePlugin.insertVariable');
                        this.variables = transport.responseText.evalJSON();
                        this.openChooser(this.variables);
                    }
                }.bind(this)
            });
            return;
        },
        openChooser: function (variables) {
            EadesigVariables.openVariableChooser(variables);
        },
        insertVariable: function (value) {
            if (this.textareaId) {
                EadesigVariables.init(this.textareaId);
                EadesigVariables.insertVariable(value);
            } else {
                EadesigVariables.closeDialogWindow();
                this.editor.execCommand('mceInsertContent', false, value);
            }
            return;
        }
    };

});