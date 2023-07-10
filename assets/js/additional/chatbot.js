$(document).ready(function () {



});


function enter_prompt() {
    var prompt = $('#user_prompt').text();

    if (prompt !== '') {

        //loading
        Swal.fire({
            title: 'The chatbot is responding...',
            html: 'Please wait...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            }
        });

        $('#new_chat_info').hide();

        //append user prompt text
        $('#conversation_body').append('<div class="row py-2 mr-5 my-1 ml-2">' +
            '    <div class="card chatbubble mr-4" style="background-color: #eaeaea; color: black; ">' +
            '        <div class="card-body">' +
            '            ' + prompt + '' +
            '        </div>' +
            '    </div>' +
            '</div>');


        // $('#conversation_body').append('<div class="row py-2 ml-5 my-1 mr-2 justify-content-end">' +
        //     '    <div class="card chatbubble ml-4" style="background-color: #007aff; color: white;">' +
        //     '        <div class="card-body">' +
        //     '            ' + response + '' +
        //     '        </div>' +
        //     '    </div>' +
        //     '</div>');


        $.ajax({
            url: base_url + "bot/chatbot/generate_response",
            type: 'POST',
            data: { prompt: prompt },
            dataType: "json",
            success: function (response) {

                //Close loading pop up
                swal.close();

                var delay = 20; // Delay in milliseconds between each character

                //append gpt response text
                $('#conversation_body').append('<div class="row py-2 ml-5 my-1 mr-2 justify-content-end">' +
                    '    <div class="card chatbubble ml-4" style="background-color: #007aff; color: white;">' +
                    '        <div class="card-body response-card"></div>' +
                    '    </div>' +
                    '</div>');

                // Append text with the writing effect
                appendTextWithDelay(response, delay);


            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'There was an error generating your response, please try again',
                })
            }
        });
    }
}

function appendTextWithDelay(text, delay) {
    var index = 0;
    var cardBody = $('.response-card:last');

    var interval = setInterval(function () {
        cardBody.append(text[index]);
        index++;

        if (index >= text.length) {
            clearInterval(interval);
        }
    }, delay);
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