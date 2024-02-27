if (window.jQuery) {
    $(document).ready(function() {
        $("[data-form-ajax]").on("submit", function(e) {
            e.preventDefault();
            const form = $(this);
            const formData = new FormData();
            formData.append("ajax", window.location.protocol + "//" + window.location.hostname);
            let inputs = form.find("input[type=\"text\"], input[type=\"hidden\"], input[type=\"number\"], input[type=\"email\"], input[type=\"radio\"]:checked, input[type=\"checkbox\"]:checked, select, textarea");
            inputs.each( function() {
                formData.append($(this).attr("name"), $(this).val());
            });
            let wrapper = form.data('message-wrapper');
            $.ajax({
                url: $(this).data('action'),
                method: "POST",
                headers: form.data('form-ajax-header') ? {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')} : {},
                data: formData,
                dataType: "JSON",
                processData: false,
                contentType: false,
                beforeSend: function(){
                    form.find("input, select, textarea, button").attr("disabled", true);
                    form.find('button[type=submit]').append($('<i id="form-spinner" class="icon-line-loader icon-spin ps-2"></i>'));
                },
                complete: function(){
                    form.find("input, select, textarea, button").removeAttr("disabled");
                    $('#form-spinner').remove();
                },
                success: (data) => {
                    if(data["status"] === 200) {
                        $(wrapper).addClass("style-msg");
                        $(wrapper).removeClass("errormsg");
                        $(wrapper).addClass("successmsg");
                        $(wrapper).find(".sb-msg").html(data["message"]);
                        $(this).trigger("reset");
                    } else if(data["status"] === 422) {
                        $(wrapper).addClass("style-msg");
                        $(wrapper).removeClass("successmsg");
                        $(wrapper).addClass("errormsg");
                        $(wrapper).find(".sb-msg").html(data["errors"]);
                    } else {
                        $(wrapper).removeClass("style-msg");
                        $(wrapper).removeClass("errormsg");
                        $(wrapper).removeClass("successmsg");
                        $(wrapper).find(".sb-msg").html("");
                    }
                    scrollToTargetAdjusted(wrapper.substring(1));
                },
            });
        });
        // First Page (Transferul Dosarelor Aprobate
        if($('[data-home-adress]').is(":checked")) {
            $( ".home-adress" ).css( "display", "none" );
        } else if($(this).is(":not(:checked)")) {
            $( ".home-adress" ).css( "display", "block" );
        }
        $('[data-home-adress]').click(function() {
            if($(this).is(":checked")) {
                $( ".home-adress" ).css( "display", "none" );
            } else if($(this).is(":not(:checked)")) {
                $( ".home-adress" ).css( "display", "block" );
            }
        });
        // First Page (Transferul Dosarelor Aprobate) END //
        // Second Page (Inscriere In Programul Casa Verde)
        $('#isBuildingAdress').click(function() {
            if($(this).is(":checked")) {
                $( ".buildingAdress" ).css( "display", "none" );
            } else if($(this).is(":not(:checked)")) {
                $( ".buildingAdress" ).css( "display", "block" );
            }
        });
        $('#isSecondRequestAFM').click(function() {
            if($(this).is(":checked")) {
                $( ".secondRequestAFM" ).css( "display", "block" );
            } else if($(this).is(":not(:checked)")) {
                $( ".secondRequestAFM" ).css( "display", "none" );
            }
        });
        $theId = 1;
        $(`#deleteParent-${$theId}`).click(function() { $(this).parent().remove(); });
        $(".coOwner-btn-add").click(function(){
            $theId++;
            $(".coOwner-btn-add").before(`
            <div>
                <h3 class="mb-1 mt-2"><b>Coproprietar</b></h3>
                <input type="text" name="nume_copro[]" class="form-control required mt-2 mb-2" value="" placeholder="Nume coproprietar">
                <input type="text" name="prenume_copro[]" class="form-control required mt-2 mb-2" value="" placeholder="Prenume coproprietar">
                <input type="text" name="cnp_copro[]" class="form-control required mt-2 mb-2" value="" placeholder="CNP coproprietar">
                <input type="text" name="domiciliu_copro[]" class="form-control required mt-2 mb-2" value="" placeholder="Domiciliu coproprietar">
                <button id="deleteParent-${$theId}" class="btn text-white bg-danger text-decoration-none coOwner-btn mt-2 mb-0"><span class="align-items-center"><i class="icon-user-minus"></i></span>&nbsp; Sterge coproprietar</button>
            </div>
            `);
            $(`#deleteParent-${$theId}`).click(function() { $(this).parent().remove(); });
        });
        $('#isCoOwner').click(function() {
            if($(this).is(":checked")) {
                $( ".coOwner" ).css( "display", "block" );
                $( ".coOwner-btn" ).css( "display", "inline-block" );
            } else if($(this).is(":not(:checked)")) {
                $( ".coOwner" ).css( "display", "none" );
                $( ".coOwner-btn" ).css( "display", "none" );
                $( "button[id^='deleteParent-']" ).parent().remove();
            }
        });
        $("input[name='data-plans-selected']").click(function() {
            $totalPlans = $('.pricing-box').length;
            for(i = 0; i <= $totalPlans; i++) {
                if($(`#data-plan-${i}`).is(":checked")) {
                    $(`#data-plan-${i}`).parent().addClass('border-color')
                } else if($(`#data-plan-${i}`).is(":not(:checked)")) {
                    $(`#data-plan-${i}`).parent().removeClass('border-color')
                }
            }
        });
        // Second Page (Inscriere In Programul Casa Verde) END //
        function scrollToTargetAdjusted(target){
            var element = document.getElementById(target);
            var headerOffset = 60;
            var elementPosition = element.getBoundingClientRect().top;
            var offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            window.scrollTo({
                 top: offsetPosition,
                 behavior: "smooth"
            });
        }
    });
}