function timedCounter(finalValue, seconds, callback) {

    var startTime = (new Date).getTime();
    var milliseconds = seconds * 1000;

    (function update() {

        var currentTime = (new Date).getTime();
        var value = finalValue * (currentTime - startTime) / milliseconds;

        if (value >= finalValue)
            value = finalValue;
        else
            setTimeout(update, 75);

        callback && callback(value);

    })();

}

function timedCounterCall(n) {

    
    timedCounter(n, 1.5, function(value){
        value = Math.floor(value);
        $('.user-count').html(value);
    });
};

function sendAnswers(inputSelector, url, question) {
    $(inputSelector).on('change', function () {
        var answers = [];
        $(inputSelector.checked).each(function () {
            answers.push($(this).val());
        });
        $.ajax({
            type: "POST",
            url: url,
            data: {'question': question, 'answer': answers}
        });
    });
}

(function (e) {
    e.fn.countdown = function (t, n) {
        function i() {
            eventDate = Date.parse(r.date) / 1e3;
            currentDate = Math.floor(e.now() / 1e3);
            if (eventDate <= currentDate) {
                n.call(this);
                clearInterval(interval)
            }
            seconds = eventDate - currentDate;
            days = Math.floor(seconds / 86400);
            seconds -= days * 60 * 60 * 24;
            hours = Math.floor(seconds / 3600);
            seconds -= hours * 60 * 60;
            minutes = Math.floor(seconds / 60);
            seconds -= minutes * 60;
            days == 1 ? thisEl.find(".timeRefDays").text("day") : thisEl.find(".timeRefDays").text("days");
            hours == 1 ? thisEl.find(".timeRefHours").text("hour") : thisEl.find(".timeRefHours").text("hours");
            minutes == 1 ? thisEl.find(".timeRefMinutes").text("minute") : thisEl.find(".timeRefMinutes").text("minutes");
            seconds == 1 ? thisEl.find(".timeRefSeconds").text("second") : thisEl.find(".timeRefSeconds").text("seconds");
            if (r["format"] == "on") {
                days = String(days).length >= 2 ? days : "0" + days;
                hours = String(hours).length >= 2 ? hours : "0" + hours;
                minutes = String(minutes).length >= 2 ? minutes : "0" + minutes;
                seconds = String(seconds).length >= 2 ? seconds : "0" + seconds
            }
            if (!isNaN(eventDate)) {
                thisEl.find(".days").text(days);
                thisEl.find(".hours").text(hours);
                thisEl.find(".minutes").text(minutes);
                thisEl.find(".seconds").text(seconds)
            } else {
                alert("Invalid date. Example: 30 Tuesday 2013 15:50:00");
                clearInterval(interval)
            }
        }
        var thisEl = e(this);
        var r = {
            date: null,
            format: null
        };
        t && e.extend(r, t);
        i();
        interval = setInterval(i, 1e3)
    }
})(jQuery);

function solveIt(reqPath, questionId, inputElement, explanationEl) {
    $.ajax({
        type: "POST",
        url: reqPath,
        data: {'question': questionId},
        success: function (data) {
            var object = JSON.parse(data);

            for (var i = 0; i < object.length; i++) {
                object[i] = object[i].id;
            }

            var explanation = object['explanation']
                .replace('\<pre>', '\<pre><code class=\'language-php\'><xmp>')
                .replace('\</pre>', '\</xmp></code></pre>');
            var answers = object['answers'];

            for (var i = 0; i < answers.length; i++) {
                answers[i] = answers[i].id;
            }

            inputElement.prop('disabled', true);
            inputElement.each(function () {
                for (var i = 0; i < answers.length; i++) {
                    if (answers[i] == $(this).attr('value')) {
                        $(this).parent().prop('class', 'alert alert-success');
                    }
                }
            });
            explanationEl.html(explanation);
        }
    });
}


function reportQuestion(form, url, questionId) {

    form.one('submit', function(e){

        e.preventDefault();
        var formSerialize = $(this).serialize() + "&questionId=" + questionId;

        $.ajax({
            type: "POST",
            url: url,
            data: formSerialize,
            success: function () {
                 $('#reportBtn')
                     .removeClass('btn-danger')
                     .addClass('btn-success')
                     .attr('value', 'Report submitted!')
                     .trigger("click")
                     .attr('disabled', true);
                $('#submitReport').attr('disabled', true);

            },
            error: function () {
                $('#reportBtn').attr('value', 'Something went wrong!').trigger("click");
            }
        })
    });

}

// setup an "add a tag" link
var $addAnswerLink = $('<a href="#" class="add_answer_link">+</a>');
var $newLinkLi = $('<li></li>').append($addAnswerLink);

// Get the ul that holds the collection of tags
var $collectionHolder = $('ul.answers');

// add the "add a tag" anchor and li to the tags ul
$collectionHolder.append($newLinkLi);

// count the current form inputs we have (e.g. 2), use that as the new
// index when inserting a new item (e.g. 2)
$collectionHolder.data('index', $collectionHolder.find(':input').length);

$addAnswerLink.on('click', function(e) {

    e.preventDefault();
    if($collectionHolder.children('li').length <= 7){
        addAnswerForm($collectionHolder, $newLinkLi);
        $addAnswerLink.attr('disabled', false);
    } else{
        $addAnswerLink.attr('disabled', true);
    }
});


function addAnswerForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);

    // also add a remove button, just for this example
    $newFormLi.append('<a href="#" class="remove-tag">x</a>');

    $newLinkLi.before($newFormLi);

    // handle the removal, just for this example
    $('.remove-tag').click(function(e) {
        e.preventDefault();

        if($collectionHolder.children('li').length > 5){
            $(this).parent().remove();
            $('.remove-tag').attr('disabled', false);
        } else{
            $('.remove-tag').attr('disabled', true);
        }
        return false;
    });
}

function deleteRecord(url, entity) {
    $('#removeModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var modal = $(this);

        modal.find('.modal-body').text('Question: ' + name);
        $('#delete').one('click', function () {

            // $('#delete').attr('disabled', true);
            $.ajax({
                type: "POST",
                url: url,
                data: 'repository=' + entity + '&id=' + id,
                success: function () {
                    button.trigger("click");
//                    button.parent().parent().hide();
                },
                error: function () {
                    button.trigger("click");
                }
            })
        });
    });
}
