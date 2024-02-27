var base_url = "http://www.genway.ro/"; //http://localhost/genway.ro/sitex/



$(document).ready(function() {

    $("a[rel^='prettyPhoto']").prettyPhoto();

    

    $("select[name='tip']").change(function(){

 

        var display = $(this).val()=="1" ? "none" : "block";

 

        $("#inputs_firma").css("display", display);

    });

    $("input[name='livrare_adresa_1']").change(function(){

 

        var display = $("input[name='livrare_adresa_1']").is(":checked") ? "block" : "none";

 

        $(".adresa_2").css("display", display);

    });

    $("#expediere").change(function(){

 

        $.get(base_url + "index_page/ajax_get_taxa_expediere/"+$(this).val(), function(msg){

 

 

            var taxa = parseFloat(msg);

 

 

            $("#taxa_expediere").html( taxa>0 ? "+" + taxa + " lei" : "0" );

 

        });

 

        $.get(base_url + "index_page/ajax_get_total_cos/"+$(this).val(), function(msg){

 

 

            $("#total_cos").html( msg );

 

        });

    });

    $("select[name='id_filtre[]']").change(function(){

 

        var id_produs = $("input[name='id_produs']").val();

 

        var id_filtre = "";

 

        $.each( $("select[name='id_filtre[]'] option:selected"), function() {

 

 

            id_filtre += "&id_filtre[]=" + $(this).val();

 

        });

 

        $.get(base_url + "index_page/ajax_get_produs_by_filtre?id_produs=" + id_produs + id_filtre, function(data){

 

 

            var obj = jQuery.parseJSON(data);

 

 

            if( !jQuery.isEmptyObject( obj ) ) {

 

 

 

                if( obj.pret!=obj.pret_intreg_format ) {

 

 

 

 

                    $("#pret_redus").html( obj.pret_intreg_format );

 

 

 

                }

 

 

 

                $("#pret_produs").html( obj.pret );

 

 

 

                $("#stoc").removeClass();

 

 

 

                $("#stoc").addClass( obj.stoc_class );

 

 

 

                $("#stoc").html( obj.stoc_text );

 

 

            }

 

        });

    });

    $('.qty_plus').click(function(event){

 

        event.preventDefault();

 

        cantitate_actuala = parseInt( $("input[name='cantitate']").val() );

 

        $("input[name='cantitate']").val( cantitate_actuala+1 );

    });

    $('.qty_minus').click(function(event){

 

        event.preventDefault();

 

        cantitate_actuala = parseInt( $("input[name='cantitate']").val() );

 

        if( cantitate_actuala>1 ) {

 

 

            $("input[name='cantitate']").val( cantitate_actuala-1 );

 

        }

    });

 

    $("#btn-form-adauga-cos").click(function(event){

 

        id_produs = parseInt( $("#form-adauga-cos input[name='id_produs']").val() );

 

        cantitate_actuala = parseInt( $("#form-adauga-cos input[name='cantitate']").val() );

 

        $.get(base_url + "index_page/ajax_verifica_stoc/" + id_produs + "/" + cantitate_actuala, function(msg){

 

 

            if( msg=="1" ) {

                $("#form-adauga-cos").unbind().submit();

            } else {

                $.prettyPhoto.open(base_url + 'index_page/ajax_popup_stoc_indisponibil/' + id_produs + '/' + cantitate_actuala + '/?iframe=true');

            }

 

        });

        event.preventDefault();

    });

 

 

    $("#abonare_newsletter").click(function(event){

 

        nume_newsletter = $("input[name='nume_newsletter']").val();

        email_newsletter = $("input[name='email_newsletter']").val();

 

        $.post(base_url + "index_page/ajax_abonare_newsletter/", {

            "nume_newsletter": nume_newsletter,

            "email_newsletter": email_newsletter

        })

 

        .done( function(msg){

            exp = msg.split("|");

            if( exp[0]=="1" ) {

                $("input[name='nume_newsletter']").val("");

                $("input[name='email_newsletter']").val("");

            }

 

            if( exp[1]!="" ) {

                alert(exp[1]);

            }

        });

        event.preventDefault();

    });

        

    $("button[name='btn_add_voucher']").click(function(event){

        event.preventDefault();

            

        var display = $("#form_add_voucher").css("display");

        if( display=="none" ) {

            $("#form_add_voucher").css("display", "table-row");

        } else {

            $("#form_add_voucher").css("display", "none");

        }

    });

        

    $("input[name='salveaza_voucher']").click(function(event){

        event.preventDefault();

            

        var cod_voucher = $("input[name='cod_voucher']").val();

            

        $.post(base_url + "index_page/ajax_check_voucher/", {

            "cod_voucher": cod_voucher

        }).done( function(msg){

            var obj = jQuery.parseJSON( msg );

            if( obj.succes == 1 ) {

                $("#frm_add_voucher").css("display", "none");

                $("#frm_delete_voucher").css("display", "block");

                $("#voucher_cos").html(

                    "<p class='subtotal'>" + 

                    "Cod cupon / discount " + obj.cod_voucher + ":" +

                    "<span class='discount'>" +

                    "-" + obj.valoare_voucher + " lei" +

                    "</span></p>"

                    );

                        

                $("#total_cos").html( obj.total_cos + " lei" );

            } else {

                alert( obj.mesaj );

            }

        });

    });

        

    $("input[name='sterge_voucher']").click(function(event){

        event.preventDefault();

            

        $("#frm_add_voucher").css("display", "block");

        $("#frm_delete_voucher").css("display", "none");

        $("#voucher_cos").html("");

        

        $.get(base_url + "index_page/ajax_remove_voucher", function(msg){

            var obj = jQuery.parseJSON( msg );

            $("#total_cos").html( obj.total_cos + " lei" );

        });

    });

    $("select[name='id_tip_plata']").change(function(){
        var tip_plata = $(this).val();
        $.get(base_url + "index_page/ajax_get_discount_plata_op/" + tip_plata).done(function(msg){
            if( msg!="0" ) {
                $.get( base_url + "index_page/ajax_get_valoare_discount_plata_op", function(msg2){
                    $("#voucher_cos").html(

                        "<p class='subtotal'>" + 

                        "Discount discount plata in avans " + msg2 + "%:" +

                        "<span class='discount'>" +

                        " " + msg + " lei" +

                        "</span></p>"

                        );
                });
            } else {
                $("#voucher_cos").html("");
            }
            
            $.get(base_url + "index_page/ajax_get_total_cos/", function(msg){
                $("#total_cos").html( msg );
            });
        });
    });

});

function updateCheckAll() {

    var checked = $("#check_all").attr("checked");

    if( checked ) {

 

        $("input[name='id[]']").attr("checked", true);

    } else {

 

        $("input[name='id[]']:checked").attr("checked", false);

    }

}

function updateSelectSrc( val, id ) {

    id = typeof( id )=="undefined" ? "select_src" : id;

    $("#" + id).html("");

    $.ajax({

 

        url: base_url + "index_page/ajax_get_localitati/"+val,

 

        success: function(msg){

 

 

            var obj = jQuery.parseJSON( msg );

 

 

            finalTxt = "";

 

 

            $.each(obj, function(i, tip){

 

 

 

                finalTxt += "<option value='"+ tip.id +"'>"+ tip.nume +"</option>";

 

 

            });

 

 

            $("#" + id).html( finalTxt );

 

        }

    });

}

function confirmSchimbaParola() {

    if( confirm("Esti sigur ca vrei sa schimbi parola?") ) {

 

        $('#form-login').submit();

 

    }

    return false;

}

function qtyPlus( rowid ) {

    cantitate_actuala = parseInt( $("#qty_" + rowid).val() );

    $("#qty_" + rowid).val( cantitate_actuala+1 );

    return false;

}

function qtyMinus( rowid ) {

    cantitate_actuala = parseInt( $("#qty_" + rowid).val() );

    if( cantitate_actuala>1 ) {

 

        $("#qty_" + rowid).val( cantitate_actuala-1 );

    }

    return false;

}

function setNotaValue( val ) {

    $("input[name='nota']").val( val );

}

function OpenInNewTab(url )

{

    var win=window.open(url, '_blank');

    win.focus();

}