/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require([
            'jquery',
            'jquery/ui'
        ], function ($) { 
         
          var accordianEnable = window.accordianEnable; 
          if(accordianEnable)
          {
                $(document).ready(function () {
                    $("#accordion").accordion({ 
                            heightStyle: "content"
                    });

               });
        }
       
     
});


function fetchFaq(categoryId,page){
    require([
            'jquery',
            'jquery/ui'
    ], function($){ 
            var accordianEnable = window.accordianEnable; 
            
            $.ajax({
                    url: window.faqAjaxUrl,
                    method: 'POST',
                    showLoader: true,
                    data: { 
                        category_id: categoryId,
                        page:page,                        
                    },
                    success: function (response) {
                    $('.faq-category li').removeClass('active');   
                    $('.faq-category li a').removeClass('disableClick');   
                    if(accordianEnable)
                    {
                      $("#accordion").accordion("destroy"); 
                    }
                    $('#faq-list').html(response);
                    $('.pager .item').removeClass('current');                    
                    $('.pager .page'+page).addClass('current');
                    $('#current-category').val(categoryId);    
                    
                    if(accordianEnable)
                    {
                        $("#accordion").accordion({ 
                            heightStyle: "content"
                        });
                    } 
                    $('#cat'+categoryId).addClass('active');
                    $('#cat'+categoryId+' a').addClass('disableClick');
                }
            });
            
        });

    } 