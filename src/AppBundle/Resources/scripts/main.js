function timedCounter(finalValue, seconds, callback) {

    var startTime = (new Date).getTime();
    var milliseconds = seconds * 1000;

    (function update() {

        var currentTime = (new Date).getTime();
        var value = finalValue * (currentTime - startTime) / milliseconds;

        if (value >= finalValue)
            value = finalValue;
        else
            setTimeout(update, 200);

        callback && callback(value);

    })();

}

timedCounter(75, 5, function (value) {
    value = Math.floor(value);
    $('.user-count').html(value);
});










function sendAnswer(inputSelector, url, question) {
    $(inputSelector).on('change', function () {
        var answer = $(inputSelector.checked).val();
        $.ajax({
            type: "POST",
            url: url,
            data: {'question': question, 'answer': answer}
        });
    });
}

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

/*function isSolved(question) {


}

/*function solveQuestion(button, reqPath, questionId, inputElement, explanationEl) {
    button.on('click', function () {
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
    });
}*/

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