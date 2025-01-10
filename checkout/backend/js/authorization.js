$(() => {

  siteurl          = $("#siteurl").attr('data-url');
  session_checkout = sessionStorage.getItem('session');

  if(typeof session_checkout == 'undefined' || session_checkout == null || session_checkout == ""){
    const setSessionCheckout = setSession();
  }else{
    const validSessionCheckout = validSession(session_checkout);
  }

   async function validSession(token){

         try {

            var {data} = await axios.get(siteurl+'/checkout/backend/session.php',{
               headers: {
                 'Authorization': 'Bearer '+session_checkout
               }
             });

             if(data.erro){
               console.log('not session defined');
               sessionStorage.removeItem('session');
               setSession();
               return false;
             }else {
               return true;
             }

         } catch (e) {
           return false;
         }

   }

   async function setSession(){

        $.post(siteurl+'/checkout/backend/session.php', function(data){
          try {
              var obj = JSON.parse(data);

              if(obj.erro){
                console.log('not session defined');
                return false;
              }else {
                sessionStorage.setItem('session', obj.session);
                return true;
              }

          } catch (e) {
            console.log(e);
            return false;
          }

        });
  }

});
