$(document).ready(function () {

    if (new_chat === "no") {
        load_history(current_con_id);
        $('#new_chat_info').hide();
    } else {
        $('#conversation_list').append('<div onclick="open_new_chat()" class="card shadow chatbubble mb-5" style=" color: black;">' +
            '<div class="card-body">' +
            '+ &nbsp New chat' +
            '</div>' +
            '</div>');

    }

});


function enter_prompt(text = "default value") {

    //Check if user click on recommended prompt
    if (text === "default value") {
        var prompt = $('#user_prompt').text();
    }
    else {
        var prompt = text;
    }

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


        $.ajax({
            url: base_url + "bot/chatbot/generate_response",
            type: 'POST',
            data: {
                prompt: prompt,
                new_chat: new_chat,
                con_id: current_con_id
            },
            dataType: "json",
            success: function (response) {

                //Close loading pop up

                var type_of_visualization = response.type_graph;
                var return_chat_id = response.chat_id;

                //new graph

                console.log(return_chat_id);
                //Bar Graph
                if (type_of_visualization === 1) {

                    $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card shadow chatbubble ml-4" style="background-color: white; color: white;">
                                <div class="card-body response-card"><canvas width="1500" id="canvas`+ return_chat_id + `"></canvas></div>
                            </div>
                        </div>
                    `);

                    var xaxis = response.xaxis;
                    var yaxis = response.yaxis;
                    new_bar_graph(xaxis, yaxis, 'canvas' + return_chat_id, response.title, response.label, response.time_frame);

                } else if (type_of_visualization === 2) {
                    //line graph
                    $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card shadow chatbubble ml-4" style="background-color: white; color: white;">
                                <div class="card-body response-card"><canvas width="1500" id="canvas`+ return_chat_id + `"></canvas></div>
                            </div>
                        </div>
                    `);

                    var xaxis = response.xaxis;
                    var yaxis = response.yaxis;
                    new_line_graph(xaxis, yaxis, 'canvas' + return_chat_id, response.title, response.label, response.time_frame);

                } else if (type_of_visualization === 3) {

                    //If there are data found
                    if (response.exist_data === 1) {
                        $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card shadow chatbubble ml-4" style="background-color: white; color: white;">
                                <div class="card-body response-card">`+ response.table_data + `</div>
                            </div>
                        </div>
                    `);
                    } else {
                        $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card shadow chatbubble ml-4" style="background-color: white; color: black;">
                                <div class="card-body response-card">No data found</div>
                            </div>
                        </div>
                    `);
                    }

                } else if(type_of_visualization === 4){
                //line graph
                    $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card shadow chatbubble ml-4" style="background-color: white; color: white;">
                                <div class="card-body response-card"><canvas width="1500" id="canvas`+ return_chat_id + `"></canvas></div>
                            </div>
                        </div>
                    `);
                    new_multi_line_graph('canvas' + return_chat_id, response.title, response.dataset, response.label);

                }

                $('#user_prompt').text("");
                swal.close();


            },
            error: function (xhr, status, error) {
                $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card chatbubble ml-4" style="background-color: #3b75f2; color: white;">
                                <div class="card-body response-card">I do not understand your question. Please try again or refer to the user guide for more information on how to structure your question</div>
                            </div>
                        </div>
                    `);
                swal.close();
            }
        });
        load_conversation(current_con_id);
        append_new_card();
        new_chat = "no";

    }
}

function append_new_card() {
    //Append new card to conversation list if its a new chat
    if (new_chat === "yes") {

        //ajax to get latest added conversation history row id
        $.ajax({
            url: base_url + "bot/chatbot/get_latest_con_id",
            method: "GET",
            dataType: "json",
            success: function (response) {

                //set current con_id to newly created con_id
                current_con_id = response.con_id;

                load_conversation(current_con_id);


            },
            error: function (xhr, status, error) {
                // Handle errors, if any
                console.error(error);
            }
        });

    }
}

function open_new_chat() {
    new_chat = "yes";
    current_con_id = 0;
    load_conversation(current_con_id);
    $('#conversation_body').empty();
    $('#conversation_body').append(`
    <div class="row justify-content-center py-2 pt-5" id="new_chat_info" style="padding-left: 20%; padding-right:20%">
        <div class="col-md-4 text-center">
            <i class="fas fa-lightbulb pr-2" style="color:#ffcd0a; font-size: 2.0rem;"></i>
            <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Examples</div>
            <button type="button" onclick="enter_prompt('Which month in the past 12 months has been the most profitable?')" class="btn btn-outline-dark mb-2">Which month in the past 12 months has been the most profitable?</button><br>
            <button type="button" onclick="enter_prompt('Name the top 5 highest selling item for the past 5 months')" class="btn btn-outline-dark mb-2">Name the top 5 highest selling item for the past 5 months</button><br>
            <button type="button" onclick="enter_prompt('Give me a sales report for this month?')" class="btn btn-outline-dark">Give me a sales report for this month?</button>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-bolt pr-2" style="color:#007AFF; font-size: 2.0rem;"></i>
            <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Capabilities</div>
            <button disabled type="button" class="btn btn-outline-dark mb-2">Remembers what user said earlier in the conversation</button><br>
            <button disabled type="button" class="btn btn-outline-dark mb-2">Allows user to provide follow-up corrections</button><br>
            <button disabled type="button" class="btn btn-outline-dark">Trained to decline inappropriate requests</button>
        </div>
        <div class="col-md-4 text-center">
            <i class="fas fa-exclamation pr-2" style="color:#FF0000; font-size: 2.0rem;"></i>
            <div class="pb-2" style="font-weight:bold; font-size: 1.2rem;">Limitation</div>
            <button disabled type="button" class="btn btn-outline-dark mb-2">May occasionally generate incorrect information</button><br>
            <button disabled type="button" class="btn btn-outline-dark mb-2">May occasionally produce harmful instructions or biased content</button><br>
            <button disabled type="button" class="btn btn-outline-dark">Limited knowledge of world and events after 2021</button>
        </div>
    </div>
`);

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



function load_history(con_id) {

    //loading pop up
    Swal.fire({
        title: 'Loading your conversation...',
        html: 'Please wait...',
        allowEscapeKey: false,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    $.ajax({
        url: base_url + "bot/chatbot/load_conversation_history",
        type: 'POST',
        data: {
            con_id: con_id,
        },
        dataType: "json",
        success: function (response) {

            //set new con_id
            current_con_id = con_id;

            $('#conversation_body').empty();


            //append chat history
            $.each(response, function (index, chat) {

                if (chat.role == 1) {

                    $('#conversation_body').append('<div class="row py-2 mr-5 my-1 ml-2">' +
                        '    <div class="card chatbubble mr-4" style="background-color: #eaeaea; color: black; ">' +
                        '        <div class="card-body">' +
                        '            ' + chat.message + '' +
                        '        </div>' +
                        '    </div>' +
                        '</div>');

                } else {

                    $('#conversation_body').append(`
                        <div class="row py-2 ml-5 my-1 mr-2 justify-content-end">
                            <div class="card chatbubble ml-4" style="background-color: #3b75f2; color: white;">
                                <div class="card-body response-card">${chat.message}</div>
                            </div>
                        </div>
                    `);

                }

            });
            //Close loading pop up
            load_conversation(con_id);
            swal.close();

        },
        error: function (xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'There was an error loading your history, please try again',
            })
        }
    });
}

function load_conversation(con_id) {

    //check if user has conversation
    $.ajax({
        url: base_url + "bot/chatbot/check_has_conversation",
        method: "GET",
        dataType: "json",
        success: function (response) {

            if (response === "yes") {

                $.ajax({
                    url: base_url + "bot/chatbot/load_convo_card",
                    type: 'GET',
                    dataType: "json",
                    success: function (response) {


                        $('#conversation_list').empty();
                        //append new chat button
                        $('#conversation_list').append('<div onclick="open_new_chat()" class="card shadow chatbubble mb-5" style=" color: black;">' +
                            '<div class="card-body" style = "font-weight:900;">' +
                            '+ &nbsp New chat' +
                            '</div>' +
                            '</div>');


                        //append chat history
                        $.each(response, function (index, card) {

                            if (card.con_id == current_con_id) {
                                $('#conversation_list').append('<div id="con' + card.con_id + '" class="card shadow convoclass chatbubble mt-2" style=" color: black; position: relative; border: 2px solid #3b75f2;">' +
                                    '<div class="card-body convobody">' +
                                    '<i class="fas fa-comments pr-2"></i>' + card.con_name + '' +
                                    '<div class="buttons_icon" id = "buttonset' + card.con_id + '" style="position: absolute; right: 5px; top: 50%; transform: translateY(-50%);">' +
                                    '<button class="edit-button text-secondary" onclick ="edit_con_name(' + card.con_id + ')" title="Edit" style="background-color: white; border: none;"><i class="fas fa-edit"></i></button>' +
                                    '<button class="delete-button text-secondary" onclick ="delete_conversation(' + card.con_id + ')" title="Delete" style="background-color: white; border: none;"><i class="fas fa-trash"></i></button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>');
                            } else {
                                $('#conversation_list').append('<div onclick="load_history(' + card.con_id + ')" id="con' + card.con_id + '" class="card shadow convoclass chatbubble mt-2" style=" color: black; position: relative;">' +
                                    '<div class="card-body convobody">' +
                                    '<i class="fas fa-comments pr-2"></i>' + card.con_name + '' +
                                    '</div>' +
                                    '</div>');
                            }

                        });

                        //Make active card effect
                        //Unset all card css
                        $('.convoclass').css({
                            'color': 'black',
                            'font-weight': 'normal'
                        });
                        //Set card as active
                        $('#con' + con_id).css({
                            'color': '#007aff',
                            'font-weight': 'bold'
                        });


                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'There was an error loading your converation history, please try again',
                        })
                    }
                });
            }

        },
        error: function (xhr, status, error) {
            // Handle errors, if any
            console.error(error);
        }
    });


}

function edit_con_name(con_id) {

    Swal.fire({
        title: 'Enter a name',
        input: 'text',
        inputPlaceholder: 'Conversation name',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        cancelButtonText: 'Cancel',
        preConfirm: (value) => {
            if (!value) {
                return Swal.showValidationMessage('Please enter a name');
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: base_url + "bot/chatbot/edit_conversation_name",
                type: 'POST',
                data: {
                    con_id: con_id,
                    con_name: result.value
                },
                success: function (response) {

                    Swal.fire({
                        icon: 'success',
                        title: 'The name has been edited',
                    })

                    load_conversation(con_id);

                },
                error: function (xhr, status, error) {
                    // Handle errors, if any
                    Swal.fire({
                        icon: 'error',
                        title: 'There was an error editing your conversation',
                    })

                }
            });

        }
    });
}

function delete_conversation(con_id) {

    Swal.fire({
        text: 'Are you sure you want to permanently delete this conversation?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#1cc88a',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: base_url + "bot/chatbot/delete_conversation",
                type: 'POST',
                data: {
                    con_id: con_id
                },
                success: function (response) {

                    window.location.href = base_url + "bot/chatbot";

                },
                error: function (xhr, status, error) {
                    // Handle errors, if any
                    Swal.fire({
                        icon: 'error',
                        title: 'There was an error deleting your conversation',
                    })

                }
            });

        }
    })
}

function new_bar_graph(itemSubcategories, itemQuantities, canvas_id, title, label, time_frame) {

    var canvas = document.getElementById(canvas_id);

    // Create the line chart
    var barChart = new Chart(canvas, {
        type: 'bar', // Change the chart type to 'bar'
        data: {
            labels: itemSubcategories, // X-axis labels
            datasets: [{
                label: label, // Label for the dataset
                data: itemQuantities, // Y-axis data
                backgroundColor: 'rgba(59, 117, 242, 0.2)', // Bar fill color
                borderColor: 'rgba(59, 117, 242, 1)', // Bar border color
                borderWidth: 1, // Bar border width
            }]
        },
        options: {
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Categories',
                        color: '#911',
                        font: {
                            family: 'Comic Sans MS',
                            size: 20,
                            weight: 'bold',
                            lineHeight: 1.2,
                        },
                        padding: { top: 20, left: 0, right: 0, bottom: 0 }
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Unit Sold',
                        color: '#191',
                        font: {
                            family: 'Times',
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: { top: 30, left: 0, right: 0, bottom: 0 }
                    }
                }
            },
            title: {
                display: true,
                text: title, // Replace with your desired chart title
                fontSize: 16, // Adjust the font size if needed
            }

        }
    });

}

function new_line_graph(item_name, item_sales, canvas_id, title, label, time_frame) {

    var canvas = document.getElementById(canvas_id);

    var lineChart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: item_name, // X-axis labels
            datasets: [{
                label: label, // Label for the dataset
                data: item_sales, // Y-axis data
                backgroundColor: 'rgba(59, 117, 242, 0.2)', // Fill color
                borderColor: 'rgba(59, 117, 242, 1)', // Line color
                borderWidth: 3, // Line width
                fill: false,
                tension: 0,
            }]
        },
        options: {
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Item Name',
                        color: '#3b75f2',
                        font: {
                            family: 'Times',
                            size: 20,
                            weight: 'bold',
                            lineHeight: 1.2,
                        },
                        padding: { top: 20, left: 0, right: 0, bottom: 0 }
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Sales Generated',
                        color: '#3b75f2',
                        font: {
                            family: 'Times',
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: { top: 30, left: 0, right: 0, bottom: 0 }
                    }
                },
            },
            title: {
                display: true,
                text: title, // Replace with your desired chart title
                fontSize: 16, // Adjust the font size if needed
            },

        }
    });
}

function new_multi_line_graph(canvas_id, title, dataset, month_label) {

    console.log(dataset);
    var canvas = document.getElementById(canvas_id);

    var lineChart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: month_label, // X-axis labels
            datasets: dataset 
        },
        options: {
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Item Name',
                        color: '#3b75f2',
                        font: {
                            family: 'Times',
                            size: 20,
                            weight: 'bold',
                            lineHeight: 1.2,
                        },
                        padding: { top: 20, left: 0, right: 0, bottom: 0 }
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Sales Generated',
                        color: '#3b75f2',
                        font: {
                            family: 'Times',
                            size: 20,
                            style: 'normal',
                            lineHeight: 1.2
                        },
                        padding: { top: 30, left: 0, right: 0, bottom: 0 }
                    }
                },
            },
            title: {
                display: true,
                text: title, // Replace with your desired chart title
                fontSize: 16, // Adjust the font size if needed
            },

        }
    });
}

