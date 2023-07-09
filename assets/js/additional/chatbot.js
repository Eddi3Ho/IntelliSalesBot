$(document).ready(function () {



});


function enter_prompt() {
    var prompt = $('#user_prompt').text();

    if (prompt !== '') {

        $('#new_chat_info').hide();

        $('#conversation_body').append('<div class="row py-2 mr-5 my-1 ml-2">' +
            '    <div class="card chatbubble mr-4" style="background-color: #eaeaea; color: black; ">' +
            '        <div class="card-body">' +
            '            ' + prompt + '' +
            '        </div>' +
            '    </div>' +
            '</div>');

            
        $('#conversation_body').append('<div class="row py-2 ml-5 my-1 mr-2 justify-content-end">' +
            '    <div class="card chatbubble ml-4" style="background-color: #007aff; color: white;">' +
            '        <div class="card-body">' +
            '            ' + sampleParagraph + '' +
            '        </div>' +
            '    </div>' +
            '</div>');

        // $.ajax({
        //     url: base_url + "bot/chatbot/generate_response",
        //     type: 'POST',
        //     data: { prompt: prompt },
        //     dataType: 'json',
        //     success: function (response) {

        //         $('#conversation_body').append('<div class="row py-2 mr-5 my-1"> \
        //                 <div class="col-xl-12"> \
        //                     <div class="card chatbubble mr-4" style="background-color: #eaeaea; color: black;"> \
        //                         <div class="card-body"> \
        //                             <p>'+''+'</p> \
        //                         </div> \
        //                     </div> \
        //                 </div> \
        //             </div>');

        //     },
        //     error: function(xhr, status, error) {
        //         // Handle errors, if any
        //         console.error(error);
        //     }
        // });
    }
}

// Dealing with Textarea Height
function calcHeight(value) {
    let numberOfLineBreaks = (value.match(/\n/g) || []).length;
    // min-height + lines x line-height + padding + border
    let newHeight = 20 + numberOfLineBreaks * 20 + 12 + 2;
    return newHeight;
}

let textarea = document.querySelector(".resize-ta");
textarea.addEventListener("keyup", () => {
    textarea.style.height = calcHeight(textarea.value) + "px";
});



function load_history() {
    // $.ajax({
    //     url: base_url + "bot/chatbot/generate_response",
    //     method:"POST",
    //     data:{ sale_id:sale_id},
    //     success:function(data)
    //     {
    //         $('#view_sale_model').html(data);

    //     }
    // });
}