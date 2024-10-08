import { jsxs, jsx } from 'react/jsx-runtime';
import Select from './select.js';
import 'react';
import SvgCircleTrash from '../graphics/icons/CircleTrash.tsx.js';
import SvgGrab from '../graphics/icons/Grab.tsx.js';
import Input from './input.js';
import FormRow from './form-row.js';
import 'react-select';
import 'react-select/async';
import '../graphics/icons/DropdownIndicator.tsx.js';
import 'classnames';
import '../graphics/icons/CircleChecked.tsx.js';
import '../graphics/icons/Info.tsx.js';

/**
 * MappingRowReversed <br />
 * Manage mapping from WordPress fields to Airtable ones <br />
 * Use a template to auto Airtable field option
 *
 * @param {MappingRowReversedTexts} texts Texts
 * @param {number} index Row index.
 * @param {string} airtableFieldId Airtable field id
 * @param {string} wordPressFieldId WordPress field id
 * @param {FieldOptions} fieldOptions Field's options
 * @param {React.ReactNode} error Error
 * @param {MappingManagerReversed} mappingManager Mapping manager
 * @param {string} className Class name
 * @param {object} ...props Other props for <tr> HTML element.
 * @constructor
 */
function MappingRowReversed({ texts, index, airtableFieldId, wordPressFieldId, fieldOptions, error, mappingManager, className = '', ...props }) {
    Object.keys(mappingManager.airtableSelectFieldGroupsOptions).length === 0; // || loadingDatabasesAndPages;
    let wordPressFieldConfig = {};
    if (wordPressFieldId) {
        wordPressFieldConfig = mappingManager.getWordPressFieldById(wordPressFieldId) ?? {};
    }
    const airtableFieldChangedHandler = (newValue) => {
        mappingManager.updateAirtableField(index, newValue ? newValue.value : '');
    };
    const wordPressFieldChangedHandler = (newValue) => {
        mappingManager.updateWordPressField(index, newValue ? newValue.value : '');
    };
    const customFieldOptionChangedHandler = (e) => {
        const target = e?.target;
        mappingManager.updateFieldOption(index, 'name', target.value);
    };
    const removeMappingRowHandler = () => {
        mappingManager.removeMappingRow(index);
    };
    let selectedWordPressOption = '';
    const wordPressOptions = Object.keys(mappingManager.wordPressSelectFieldsOptions).map((groupKey) => {
        const group = mappingManager.wordPressSelectFieldsOptions[groupKey];
        return {
            label: group.label,
            options: group.options.map(({ value, label, enabled }) => {
                const option = { value, label, isDisabled: !enabled };
                if (wordPressFieldId === option.value) {
                    selectedWordPressOption = option;
                }
                return option;
            })
        };
    });
    let selectedAirtableField = '';
    const airtableOptions = Object.keys(mappingManager.airtableSelectFieldGroupsOptions[index]).map((groupKey) => {
        const group = mappingManager.airtableSelectFieldGroupsOptions[index][groupKey];
        const groupLabel = group.label ?? texts.fields;
        return {
            label: groupLabel,
            options: group.options.map((field) => {
                const option = { value: field.id, label: field.name };
                if (option.value === airtableFieldId) {
                    selectedAirtableField = option;
                }
                return option;
            })
        };
    });
    const renderCustomFieldOptions = () => {
        return jsx(FormRow, { className: "airwpsync-c-mapping-row-reversed__custom-field", children: jsx(Input, { label: texts.custom_field, labelHidden: true, placeholder: texts.custom_field, value: fieldOptions.name ?? '', onChange: customFieldOptionChangedHandler }) });
    };
    return jsxs("tr", { className: `airwpsync-c-mapping-row-reversed ${className}`, ...props, children: [jsx("td", { className: "airwpsync-c-mapping-row-reversed__wordpress-field-col", children: jsxs("div", { className: "airwpsync-c-mapping-row-reversed__wordpress-field", children: [jsxs("div", { className: "airwpsync-c-mapping-row-reversed__btn-sort airwpsync-u-reset-btn", children: [jsx("span", { className: "screen-reader-text", children: texts.sort }), jsx(SvgGrab, {})] }), jsx(Select, { label: texts.wordpress_field_placeholder, labelHidden: true, value: selectedWordPressOption, 
                            // @ts-ignore
                            onChange: wordPressFieldChangedHandler, 
                            // @ts-ignore
                            options: wordPressOptions, placeholder: texts.wordpress_field_placeholder }), wordPressFieldConfig.notice ? jsx("small", { children: wordPressFieldConfig.notice }) : null, wordPressFieldId && wordPressFieldId.split('::')[1] === 'custom_field' ? renderCustomFieldOptions() : null] }) }), jsx("td", { className: "airwpsync-c-mapping-row-reversed__assigned-to", children: texts.assigned_to }), jsxs("td", { className: "airwpsync-c-mapping-row-reversed__airtable-field-col", children: [jsx(Select, { label: '', value: selectedAirtableField, 
                        // @ts-ignore
                        onChange: airtableFieldChangedHandler, 
                        // @ts-ignore
                        options: airtableOptions }), error ? jsx("div", { className: "airwpsync-c-mapping-row-reversed__error", children: error }) : null] }), jsx("td", { className: "airwpsync-c-mapping-row-reversed__flex", children: jsxs("button", { className: "airwpsync-c-mapping-row-reversed__remove-btn airwpsync-u-reset-btn", type: "button", onClick: removeMappingRowHandler, children: [jsx("span", { className: "screen-reader-text", children: texts.remove }), jsx(SvgCircleTrash, {})] }) })] });
}

export { MappingRowReversed as default };
//# sourceMappingURL=mapping-row-reversed.js.map
