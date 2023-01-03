<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $project->project_name }}</title>
        <style>
            /*custom font*/
            @import url(https://fonts.googleapis.com/css?family=Montserrat);

            /*basic reset*/
            * {margin: 0; padding: 0;}

            html {
                height: 100%;
            }

            body {
                font-family: montserrat, arial, verdana;
            }
            /*form styles*/
            #former {
                margin: 50px auto;
                /*text-align: center;*/
                position: relative;
            }
            #former fieldset {
                background: white;
                border: 0 none;
                border-radius: 3px;
                box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.4);
                padding: 20px 30px;
                box-sizing: border-box;
                width: 80%;
                margin: 0 10%;
                
                /*stacking fieldsets above each other*/
                position: relative;
            }
            /*Hide all except first fieldset*/
            #former fieldset:not(:first-of-type) {
                display: none;
            }
            /*inputs*/
            #former input, #former textarea {
                padding: 15px;
                border: 1px solid #ccc;
                border-radius: 3px;
                margin-bottom: 10px;
                width: 100%;
                box-sizing: border-box;
                font-family: montserrat;
                color: #2C3E50;
                font-size: 13px;
            }
            /*buttons*/
            #former .action-button {
                width: 100px;
                background: #27AE60;
                font-weight: bold;
                color: white;
                border: 0 none;
                border-radius: 1px;
                cursor: pointer;
                padding: 10px;
                margin: 10px 5px;
                text-decoration: none;
                font-size: 14px;
            }
            #former .action-button:hover, #former .action-button:focus {
                box-shadow: 0 0 0 2px white, 0 0 0 3px #27AE60;
            }
            /*headings*/
            .fs-title {
                font-size: 15px;
                text-transform: uppercase;
                color: #2C3E50;
                margin-bottom: 10px;
                text-align: center;
            }
            .fs-subtitle {
                font-weight: normal;
                font-size: 13px;
                color: #666;
                margin-bottom: 20px;
            }
            /*progressbar*/
            #progressbar {
                margin-bottom: 30px;
                overflow: hidden;
                /*CSS counters to number the steps*/
                counter-reset: step;
            }
            #progressbar li {
                list-style-type: none;
                color: white;
                text-transform: uppercase;
                font-size: 9px;
                width: 33.33%;
                float: left;
                position: relative;
            }
            #progressbar li:before {
                content: counter(step);
                counter-increment: step;
                width: 20px;
                line-height: 20px;
                display: block;
                font-size: 10px;
                color: #333;
                background: white;
                border-radius: 3px;
                margin: 0 auto 5px auto;
            }
            /*progressbar connectors*/
            #progressbar li:after {
                content: '';
                width: 100%;
                height: 2px;
                background: white;
                position: absolute;
                left: -50%;
                top: 9px;
                z-index: -1; /*put it behind the numbers*/
            }
            #progressbar li:first-child:after {
                /*connector not needed before the first step*/
                content: none; 
            }
            /*marking active/completed steps green*/
            /*The number of the step and the connector before it = green*/
            #progressbar li.active:before,  #progressbar li.active:after{
                background: #27AE60;
                color: white;
            }
        </style>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href='{{ asset('templates/'.$project->template_id.'.css') }}'/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src='{{ asset('js/app.js') }}'></script>
        <script>
            var self = this;
            $.ajax({
                type: 'GET',
                dataType: "json",
                contentType: 'application/json',
                url: "../../../sheet/json/" + {{ $sheet->id }},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(data) {
                    former(JSON.parse(data.json));
                },
                error: function (textStatus, errorThrown) {
                    builder.setForm({components: []});
                }
            });
            function former(data){
                let formElement = document.getElementById('inner');
                $('<script>', { type : 'text/javascript', src : "../../../js/button.override.js" }).appendTo('body');
                Formio.createForm(formElement, data).then((form) => {
                    // Prevent the submission from going to the form.io server.
                    form.nosubmit = true;
                    form.on('submit', function(submission) {
                        console.log(submission);
                        form.emit('submitDone', submission)
                        /*console.log(submission);
                        const Url = config.routes.createSheet;
                        return Formio.fetch(Url, {
                            body: JSON.stringify(submission),
                            headers: {
                                'content-type': 'application/json'
                            },
                            method: 'POST',
                            mode: 'cors',
                        }).then(function(response) {
                            form.emit('submitDone', submission)
                            response.json()
                        })*/
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
            }
        </script>
        <!--script>
            //jQuery time
            var current_fs, next_fs, previous_fs; //fieldsets
            var left, opacity, scale; //fieldset properties which we will animate
            var animating; //flag to prevent quick multi-click glitches

            $(".next").click(function(){
                if(animating) return false;
                animating = true;
                
                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                
                //activate next step on progressbar using the index of next_fs
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
                
                //show the next fieldset
                next_fs.show(); 
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale current_fs down to 80%
                        scale = 1 - (1 - now) * 0.2;
                        //2. bring next_fs from the right(50%)
                        left = (now * 50)+"%";
                        //3. increase opacity of next_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({
                    'transform': 'scale('+scale+')',
                    'position': 'absolute'
                });
                        next_fs.css({'left': left, 'opacity': opacity});
                    }, 
                    duration: 800, 
                    complete: function(){
                        current_fs.hide();
                        animating = false;
                    }, 
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });

            $(".previous").click(function(){
                if(animating) return false;
                animating = true;
                
                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();
                
                //de-activate current step on progressbar
                $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
                
                //show the previous fieldset
                previous_fs.show(); 
                //hide the current fieldset with style
                current_fs.animate({opacity: 0}, {
                    step: function(now, mx) {
                        //as the opacity of current_fs reduces to 0 - stored in "now"
                        //1. scale previous_fs from 80% to 100%
                        scale = 0.8 + (1 - now) * 0.2;
                        //2. take current_fs to the right(50%) - from 0%
                        left = ((1-now) * 50)+"%";
                        //3. increase opacity of previous_fs to 1 as it moves in
                        opacity = 1 - now;
                        current_fs.css({'left': left});
                        previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
                    }, 
                    duration: 800, 
                    complete: function(){
                        current_fs.hide();
                        animating = false;
                    }, 
                    //this comes from the custom easing plugin
                    easing: 'easeInOutBack'
                });
            });
        </script-->
    </head>
    <body class="formarea">        
        <div>
            <div>
                <div>
                    <form id="former">
                        <!-- progressbar -->
                        <!--ul id="progressbar">
                          <li class="active">Account Setup</li>
                          <li>Social Profiles</li>
                          <li>Personal Details</li>
                        </ul-->
                        <fieldset id="formio">
                            <h2 class="fs-title">{{ $sheet->sheet_name }}</h2>
                            <div id="inner">
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
