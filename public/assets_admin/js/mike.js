
$(document).ready(function() {

    let listCategorie = ["standard", "superieure", "luxe"];
    let listCapacite = ["simple", "double", "triple" , "quadruple"];

    // permettant d'ajouter une nouvelle chambre
    $("#ajout_gestion_chambres").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "gestion_chambres",
            method: "POST",
            data: $("#ajout_gestion_chambres").serialize() ,
            dataType: "json"
        })
        .done(function( data ) {
            if(!data.errors){
                let codeHtml = '<tr> <td>'+$("#numchambre").val()+'</td> <td>'+listCategorie[$("#categorie").val()-1]+'</td> <td>'+listCapacite[$("#capacite").val()-1]+'</td> <td>'+$("#telephone").val()+'</td> <td>'+$("#prix").val()+'</td> <td>libre</td> <td><a href="/hotel/public/admin/gestion_chambres/modification/'+data.id+'"><span class="glyphicon glyphicon-pencil alignement" <="" span=""></span></a></td> <td><a href="/hotel/public/admin/gestion_chambres/suppression/'+data.id+'"><span class="glyphicon glyphicon-trash alignement" <="" span=""></span></a></td> </tr>';
                let validation = "<div class='alert alert-success text-center'>Vous avez bien ajouté une nouvelle chambre "+$("#numchambre").val()+"</div>";
                $( "tbody" ).append(codeHtml);
                $( ".message" ).append(validation);
            }
            else{
                let pb = "<div class='alert alert-danger text-center'>Vous avez une erreur,<br><ul> " + data.message +"</ul><br></div>" ;
                $(".message").html(pb);
            }
        });
    });

    // permettant d'ajouter un nouveau service
    $("#ajout_gestion_services").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: "gestion_services",
            method: "POST",
            data: $("#ajout_gestion_services").serialize() ,
            dataType: "json"
        })
        .done(function( data ) {
            if(!data.errors){
                let codeHtml = '<tr><td>'+$("#nom_service").val()+'</td><td>'+$("#prix_service").val()+'</td><td><a href="/hotel/public/admin/gestion_services/modification/'+data.id+'"><span class="glyphicon glyphicon-pencil" <="" span=""></span></a></td><td><a href="/hotel/public/admin/gestion_services/suppression/'+data.id+'"><span class="glyphicon glyphicon-trash" <="" span=""></span></a></td></tr>';
                let validation = "<div class='alert alert-success text-center'>Vous avez bien ajouté un service "+$("#nom_service").val()+"</div>";
                $( "tbody" ).append(codeHtml);
                $( "message" ).append(validation);
            }
            else{
                let pb = "<div class='alert alert-danger text-center'>Vous avez une erreur,<br><ul>" + data.message +"</ul><br></div>";
                $(".message").html(pb);
            }
        });
    });
});
