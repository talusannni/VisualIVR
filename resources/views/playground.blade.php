<x-layout>
  <div class="container py-4">
    <!--link rel="stylesheet" href="{{ asset('css/jquery.flowchart.min.css') }}"-->
    <link rel="stylesheet" href="{{ asset('css/formio.full.min.css') }}">
    <button onclick="save()">Save</button>
    <div id="tabs">
      <ul>
        <li><a href="#tabs-1">Form Builder</a></li>
        <li><a href="#tabs-2">Preview</a></li>
        <!--li><a href="#tabs-3">Aenean lacinia</a></li-->
      </ul>
      <div id="tabs-1">
        <div id='builder'></div>
      </div>
      <div id="tabs-2">
        <div id="formio">No Preview Available!</div>
      </div>
      <div id="tabs-3">
        <div id="json"></div>
      </div>
    </div>

    <script type="text/javascript">
      var jsonElement = document.getElementById('json');
      var formElement = document.getElementById('formio');
      var builder = new Formio.FormBuilder(document.getElementById("builder"),{}, {
        noNewEdit: true,
        noDefaultSubmitButton: true,
        alwaysConfirmComponentRemoval: true,
        builder: {
          resource: false,
          premium: false,
          custom: {
            title: 'Pre-Defined Fields',
            weight: 10,
            components: {
                firstName: {
                    title: 'First Name',
                    key: 'firstName',
                    icon: 'terminal',
                    schema: {
                        label: 'First Name',
                        type: 'textfield',
                        key: 'firstName',
                        input: true
                    }
                },
                lastName: {
                    title: 'Last Name',
                    key: 'lastName',
                    icon: 'terminal',
                    schema: {
                      label: 'Last Name',
                      type: 'textfield',
                      key: 'lastName',
                      input: true
                    }
                },
                email: {
                    title: 'Email',
                    key: 'email',
                    icon: 'at',
                    schema: {
                        label: 'Email',
                        type: 'email',
                        key: 'email',
                        input: true
                    }
                },
                phoneNumber: {
                    title: 'Mobile Phone',
                    key: 'mobilePhone',
                    icon: 'phone-square',
                    schema: {
                        label: 'Mobile Phone',
                        type: 'phoneNumber',
                        key: 'mobilePhone',
                        input: true
                    }
                }
            }
          }
        }
      });
      
      var onBuild = function(build) {
        jsonElement.innerHTML = '';
        jsonElement.appendChild(document.createTextNode(JSON.stringify(builder.instance.schema, null, 4)));
        Formio.createForm(formElement, builder.instance.form).then((form) => {
          // Prevent the submission from going to the form.io server.
          form.nosubmit = true;
          form.on('submit', function(submission) {
            console.log(submission);
            const Url = @json(route('Sheet.store'));
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

      function save(){
        var jsonElement = $('#json');
        const Url = @json(route('Sheet.store'));

        $.ajax({
            type: 'POST',
            url: Url,
            data: jsonElement.html(),
            headers: {accept: 'application/json', 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
          success: function (response) {
            
          },
          error: function (error) {
          
          }
        });
      }
    </script>
    <script src='{{ asset('js/override.js') }}'></script>
    <!--div id="chart_container">
      <div class="flowchart-container" id="playArea"></div>
    </div>
    <div id="operator_properties" style="display: block;">
      <label for="operator_title">Operator's title: </label><input type="text" id="operator_title">
    </div>
    <button class="delete_selected_button">Delete selected operator / link</button>
    <div class="draggable_operators">
        <div class="draggable_operators_label">
        Operators (drag and drop them in the flowchart):
        </div>
        <div class="draggable_operators_divs">
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="0" data-nb-outputs="1">Start</div>
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="1" data-nb-outputs="1">Internal Page</div>
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="1" data-nb-outputs="1">Form</div>
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="1" data-nb-outputs="0">External Page</div>
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="1" data-nb-outputs="1">Send Email</div>
          <div class="draggable_operator ui-draggable ui-draggable-handle" data-nb-inputs="1" data-nb-outputs="1">Log</div>
        </div>
      </div>
    <style>
		.flowchart-container {
			width: 800px;
			height: 400px;
			background: white;
			border: 1px solid #BBB;
			margin-bottom: 10px;
		}
    </style>
    <script src="{{ asset('js/jquery.flowchart.min.js') }}"></script>
    <script>
        $(document).ready(function() {
    var $flowchart = $('#playArea');
    var $container = $flowchart.parent();
    
    var cx = $flowchart.width() / 2;
    var cy = $flowchart.height() / 2;
    
    var data = {      
      links: {
        link_1: {
          fromOperator: 'operator1',
          fromConnector: 'output_1',
          toOperator: 'operator2',
          toConnector: 'input_2',
        },
      }
    };
    
    var $operatorProperties = $('#operator_properties');
    var $linkProperties = $('#link_properties');
    var $operatorTitle = $('#operator_title');
    var $linkColor = $('#link_color');

    // Apply the plugin on a standard, empty div...
    $flowchart.flowchart({
      data: data.links,
      onOperatorSelect: function(operatorId) {
        $operatorProperties.show();
        $operatorTitle.val($flowchart.flowchart('getOperatorTitle', operatorId));
        return true;
      },
      onOperatorUnselect: function() {
        $operatorProperties.hide();
        return true;
      },
      onLinkSelect: function(linkId) {
        $linkProperties.show();
        $linkColor.val($flowchart.flowchart('getLinkMainColor', linkId));
        return true;
      },
      onLinkUnselect: function() {
        $linkProperties.hide();
        return true;
      }
    });

    $flowchart.parent().siblings('.delete_selected_button').click(function() {
      $flowchart.flowchart('deleteSelected');
    });
    
    $operatorTitle.keyup(function() {
      var selectedOperatorId = $flowchart.flowchart('getSelectedOperatorId');
      if (selectedOperatorId != null) {
        $flowchart.flowchart('setOperatorTitle', selectedOperatorId, $operatorTitle.val());
      }
    });
    
    $linkColor.change(function() {
      var selectedLinkId = $flowchart.flowchart('getSelectedLinkId');
      if (selectedLinkId != null) {
        $flowchart.flowchart('setLinkMainColor', selectedLinkId, $linkColor.val());
      }
    });

    $flowchart.siblings('.delete_selected_button').click(function() {
      $flowchart.flowchart('deleteSelected');
    });
    
    var $draggableOperators = $('.draggable_operator');
    
    function getOperatorData($element) {
      var nbInputs = parseInt($element.data('nb-inputs'));
      var nbOutputs = parseInt($element.data('nb-outputs'));
      var data = {
        properties: {
          title: $element.text(),
          inputs: {},
          outputs: {}
        } 
      };
      
      var i = 0;
      for (i = 0; i < nbInputs; i++) {
        data.properties.inputs['input_' + i] = {
          label: 'Input ' + (i + 1)
        };
      }
      for (i = 0; i < nbOutputs; i++) {
        data.properties.outputs['output_' + i] = {
          label: 'Output ' + (i + 1)
        };
      }
      
      return data;
    }
    
    var operatorId = 0;
        
    $draggableOperators.draggable({
        cursor: "move",
        opacity: 0.7,
        
        helper: 'clone', 
        appendTo: 'body',
        zIndex: 1000,
        
        helper: function(e) {
          var $this = $(this);
          var data = getOperatorData($this);
          return $flowchart.flowchart('getOperatorElement', data);
        },
        stop: function(e, ui) {
            var $this = $(this);
            var elOffset = ui.offset;
            var containerOffset = $container.offset();
            if (elOffset.left > containerOffset.left &&
                elOffset.top > containerOffset.top && 
                elOffset.left < containerOffset.left + $container.width() &&
                elOffset.top < containerOffset.top + $container.height()) {

                var flowchartOffset = $flowchart.offset();

                var relativeLeft = elOffset.left - flowchartOffset.left;
                var relativeTop = elOffset.top - flowchartOffset.top;

                var positionRatio = $flowchart.flowchart('getPositionRatio');
                relativeLeft /= positionRatio;
                relativeTop /= positionRatio;
                
                var data = getOperatorData($this);
                data.left = relativeLeft;
                data.top = relativeTop;
                
                $flowchart.flowchart('addOperator', data);
            }
        }
    });
    
    
  });
    </script-->
  </div>
</x-layout>