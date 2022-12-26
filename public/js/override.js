let builder = {};
let jsonElement = document.getElementById('json');
let formElement = document.getElementById('formio');
function formBuilder(data={components: []}){
  Formio.icons = 'fontawesome';
  //Formio.use(FormioPrettyCheckboxes);
  builder = new Formio.FormBuilder(document.getElementById("builder"), data, {
    noNewEdit: true,
    noDefaultSubmitButton: true,
    alwaysConfirmComponentRemoval: true,
    builder: {
      resource: false,
      premium: false
    }
  });
}
formBuilder();
var onBuild = function(build) {
  jsonElement.innerHTML = '';
  jsonElement.appendChild(document.createTextNode(JSON.stringify(builder.instance.schema, null, 4)));
  localStorage.setItem($(".pageName.active").attr("id"), JSON.stringify(builder.instance.schema, null, 4));
  Formio.createForm(formElement, builder.instance.form).then((form) => {
    // Prevent the submission from going to the form.io server.
    form.nosubmit = true;
    form.on('submit', function(submission) {
      console.log(submission);
      const Url = config.routes.createSheet;
      return Formio.fetch(Url, {
          body: JSON.stringify(submission),
          headers: {
            'content-type': 'application/json'
          },
          method: 'POST',
          mode: 'cors',
        })
        .then(function(response) {
          form.emit('submitDone', submission)
          response.json()
        })
    });
    // What to do when the submit begins.
    form.on('submitDone', function(submission) {
      //window.location = '/app/thanks.html';
    });
    // What to do when the submit failed.
    form.on('submitError', function(submission) {
      //window.location = '/app/thanks.html';
    });
    form.on('componentChanged', (changed) => {
      console.log('Data was changed!', changed);
    });
    /*if (!form.checkValidity(null, false, null, true)) {
      alert('The form is invalid!');
    }*/
  });
};

var onReady = function() {
  var formElement = document.getElementById('formio');
  builder.instance.on('change', onBuild);
};

var setDisplay = function(display) {
  builder.setDisplay(display).then(onReady);
};

builder.instance.ready.then(onReady);

Formio.Components.components.textfield.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'textfield',
          key: 'placeholder',
          label: 'Placeholder'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'checkbox',
          key: 'hideLabel',
          label: 'Hide Label'
        }
      ]
    };
  };

  Formio.Components.components.textarea.editForm = function() {
    return {
      components: [
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'textfield',
          key: 'placeholder',
          label: 'Placeholder'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'checkbox',
          key: 'hideLabel',
          label: 'Hide Label'
        },
        {
          type: 'checkbox',
          key: 'autoExpand',
          label: 'Auto Expand'
        },
        {
          type: 'textfield',
          key: 'rows',
          label: 'Rows'
        },
        {
          type: 'select',
          key: 'editor',
          label: 'Editor',
          "data": {
            "values": [
                {
                    "label": "ACE",
                    "value": "ace"
                },
                {
                    "label": "CKEditor",
                    "value": "ckeditor"
                },
                {
                    "label": "Quill",
                    "value": "quill"
                }
            ],
          }
        }
      ]
    };
  };

  Formio.Components.components.number.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'textfield',
          key: 'placeholder',
          label: 'Placeholder'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'checkbox',
          key: 'hideLabel',
          label: 'Hide Label'
        }
      ]
    };
  };


  Formio.Components.components.password.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'select',
          key: 'validateOn',
          label: 'Validate On',
          "data": {
            "values": [
                {
                    "label": "Change",
                    "value": "change"
                }
            ],
          }
        },
        {
          type: 'textfield',
          key: 'validate.minLength',
          label: 'Minimum Length'
        },
        {
          type: 'textfield',
          key: 'validate.maxLength',
          label: 'Maximum Length'
        },
        {
          type: 'textfield',
          key: 'placeholder',
          label: 'Placeholder'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'checkbox',
          key: 'hideLabel',
          label: 'Hide Label'
        }
      ]
    };
  };
 
  Formio.Components.components.checkbox.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'checkbox',
          key: 'hideLabel',
          label: 'Hide Label'
        }
      ]
    };
  };

  Formio.Components.components.selectboxes.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'datagrid',
          key: 'values',
          label: 'Values',
          input: true,
          reorder: true,
          defaultValue: [{
            label: '',
            value: ''
          }],
          components: [{
            label: 'Label',
            key: 'label',
            input: true,
            type: 'textfield'
          }, {
            label: 'Value',
            key: 'value',
            input: true,
            type: 'textfield',
            validate: {
              required: true
            }
          }],
          tooltip: 'The radio button values that can be picked for this field. Values are text submitted with the form data. Labels are text that appears next to the radio buttons on the form.'
        }
      ]
    };
  };
  
  Formio.Components.components.select.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'select',
          key: 'widget',
          label: 'Widget Type',
          defaultValue: "choicesjs",
          "data": {
            "values": [
                {
                    "value": "choicesjs",
                    "label": "ChoicesJS"
                },
                {
                    "value": "html5",
                    "label": "HTML 5"
                }
            ],
          }
        },
        {
          type: 'select',
          key: 'dataSrc',
          label: 'Data Source Type',
          "data": {
            "values": [
                {
                    "value": "values",
                    "label": "Values"
                }/*,
                {
                    "value": "url",
                    "label": "URL"
                },
                {
                    "value": "json",
                    "label": "Raw JSON"
                }*/
            ],
          }
        },
        {
          type: 'textfield',
          key: 'data.url',
          label: 'Data Source URL',
          placeholder: 'Data Source URL',
          tooltip: 'A URL that returns a JSON array to use as the data source.',
          conditional: {
            json: { '===': [{ "var": 'data.dataSrc' }, 'url'] }
          }
        },
        {
          type: 'textarea',
          as: 'json',
          editor: 'ace',
          weight: 10,
          input: true,
          key: 'data.json',
          label: 'Data Source Raw JSON',
          tooltip: 'A valid JSON array to use as a data source.',
          description: '<div>Example: <pre>["apple", "banana", "orange"].</pre></div> <div>Example 2: <pre>[{"name": "John", "email": "john.doe@test.com"}, {"name": "Jane", "email": "jane.doe@test.com"}].</pre></div>',
          conditional: {
            json: { '===': [{ "var": 'data.dataSrc' }, 'json'] }
          }
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'datagrid',
          key: 'data.values',
          label: 'Data Source Values',
          input: true,
          reorder: true,
          defaultValue: [{
            label: '',
            value: ''
          }],
          components: [{
            label: 'Label',
            key: 'label',
            input: true,
            type: 'textfield'
          }, {
            label: 'Value',
            key: 'value',
            input: true,
            type: 'textfield',
            validate: {
              required: true
            }
          }],
          tooltip: 'Values to use as the data source. Labels are shown in the select field. Values are the corresponding values saved with the submission.'
        }
      ]
    };
  };

  Formio.Components.components.radio.editForm = function() {
    return {
      components: [            
        {
          type: 'checkbox',
          key: 'validate.required',
          label: 'Required'
        },
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'datagrid',
          key: 'values',
          label: 'Values',
          input: true,
          reorder: true,
          defaultValue: '',
          components: [{
            label: 'Label',
            key: 'label',
            input: true,
            type: 'textfield'
          }, {
            label: 'Value',
            key: 'value',
            input: true,
            type: 'textfield',
            validate: {
              required: true
            }
          }],
          tooltip: 'The radio button values that can be picked for this field. Values are text submitted with the form data. Labels are text that appears next to the radio buttons on the form.'
        }
      ]
    };
  };
  
  Formio.Components.components.button.editForm = function() {
    return {
      components: [            
        {
          type: 'textfield',
          key: 'label',
          label: 'Label'
        },
        {
          type: 'checkbox',
          key: 'disabled',
          label: 'Disabled'
        },
        {
          type: 'textfield',
          key: 'next',
          label: 'Next'
        },
        {
          type: 'select',
          key: 'action',
          label: 'Action',
          defaultValue: 'submit',
          "data": {
            "values": [
                {
                    "value": "submit",
                    "label": "Submit"
                },
                {
                    "value": "reset",
                    "label": "Reset"
                }
            ],
          }
        },
        {
          type: 'checkbox',
          key: 'showValidations',
          label: 'Show Validations'
        },
        {
          type: 'select',
          key: 'theme',
          label: 'Theme',
          defaultValue: 'primary',
          "data": {
            "values": [
                {
                    "value": "primary",
                    "label": "Primary"
                },
                {
                    "value": "secondary",
                    "label": "Secondary"
                },
                {
                    "value": "info",
                    "label": "Info"
                },
                {
                    "value": "success",
                    "label": "Success"
                },
                {
                    "value": "danger",
                    "label": "Danger"
                },
                {
                    "value": "warning",
                    "label": "Warning"
                }
            ],
          }
        },
        {
          type: 'select',
          key: 'size',
          label: 'Size',
          defaultValue: 'md',
          "data": {
            "values": [
                {
                    "value": "sm",
                    "label": "Small"
                },
                {
                    "value": "md",
                    "label": "Medium"
                },
                {
                    "value": "lg",
                    "label": "Large"
                }
            ],
          }
        }
      ]
    };
  };

  const Input = Formio.Components.components.button;

  class MyComponent extends Input {
      /**
       * This is the default schema of your custom component. It will "derive"
       * from the base class "schema" and extend it with its default JSON schema
       * properties. The most important are "type" which will be your component
       * type when defining new components.
       *
       * @param extend - This allows classes deriving from this component to 
       *                 override the schema of the overridden class.
       */
       static schema(...extend) {
        return Input.schema({
            type: 'button',
            label: 'Submit',
            key: 'submit',
            next: '',
        }, ...extend);
      }
      get inputInfo() {
          const info = super.inputInfo;
          info.attr.id = this.component.next;
          return info;
      }
  }

  Formio.Components.components.button = MyComponent;
  Formio.Components.setComponents(Formio.Components.components.button);