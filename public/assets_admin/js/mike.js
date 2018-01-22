
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
                let validation = "Vous avez bien ajouté une nouvelle chambre "+$("#numchambre").val();
                $( "tbody" ).append(codeHtml);
                $( "tbody" ).append(validation);
            }
          })
          .fail(function(data) {
            if(data.errors){
                let pb = "Vous avez une erreur " + data.message ;
                $("#titre").append(pb);
          }});
    })

    $(".glyphicon-trash").click(function(){
        $.ajax({
            url: "gestion_chambres",
            method: "get",
            data: $("#resultat").serialize() ,
            // dataType: "json"
          })
          .done(function( data ) {
            // if(!data.errors){
                // let codeHtml = '<tr> <td>'+$("#numchambre").val()+'</td> <td>'+listCategorie[$("#categorie").val()-1]+'</td> <td>'+listCapacite[$("#capacite").val()-1]+'</td> <td>'+$("#telephone").val()+'</td> <td>'+$("#prix").val()+'</td> <td>libre</td> <td><a href="/hotel/public/admin/gestion_chambres/modification/'+data.id+'"><span class="glyphicon glyphicon-pencil alignement" <="" span=""></span></a></td> <td><a href="/hotel/public/admin/gestion_chambres/suppression/'+data.id+'"><span class="glyphicon glyphicon-trash alignement" <="" span=""></span></a></td> </tr>';
                let validation = "Vous avez bien supprimé une chambre ";
                // $( "tbody" ).append(codeHtml);
                $( "tbody" ).append(validation);
            // }
          })
    })
})

// {error : true, message: "frferfre"}
// {error : false, id: 2}