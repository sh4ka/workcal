/**
 * Created by jesus on 24/01/14.
 */
$( document ).ready(function() {

    var curDate = new Date();

    var calendarOptions = {
        'option1': 'value1'
    }

    // functions
    var dayClick = function(date, element){
        removeClasses();
        $(element).addClass('selectedDate')
        curDate = date;
        $('#calendar').fullCalendar('gotoDate', date);
        setDateInputs()
        getDayAjax();
    }

    var fillBdayData = function(data){
        $('#mundoreader_calendarbundle_day_gift_name').val(data.name)
        $('#mundoreader_calendarbundle_day_gift_link').val(data.link)
        $('#mundoreader_calendarbundle_day_gift_price').val(data.price)
        $('#mundoreader_calendarbundle_day_user').val(data.userId)
    }

    var clearBdayData = function(){
        $('#mundoreader_calendarbundle_day_gift_name').val('')
        $('#mundoreader_calendarbundle_day_gift_link').val('')
        $('#mundoreader_calendarbundle_day_gift_price').val('')
        $('#mundoreader_calendarbundle_day_user').val('')
    }

    var getDayAjax = function(){
        var $url = '/day/get';
        $.ajax({
            type: "POST",
            url: $url,
            data: {
                date: curDate
            }
        }).done(function( result ) {
            if(result.success) {
                fillBdayData(result)
            } else {
                clearBdayData()
            }
        });
    }

    var getEventsAjax = function(){
        var $url = '/day/events';
        calendarId = location.toString().split('/').pop();
        $.ajax({
            type: "POST",
            url: $url,
            data: {
                calendarId: calendarId,
                date: curDate
            }
        }).done(function( result ) {
            if(result.success) {
                return result.events;
            } else {
                return null;
            }
        });
    }

    var removeClasses = function(){
        $('#calendar').find('.fc-day').removeClass('selectedDate');
        $('#calendar').find('.fc-day').removeClass('fc-today');
        $('#calendar').find('.fc-day').removeClass('fc-state-highlight');
    }

    var setDateInputs = function(){
        $('#mundoreader_calendarbundle_day_date_year').val(curDate.getFullYear())
        $('#mundoreader_calendarbundle_day_date_month').val(curDate.getMonth()+1)
        $('#mundoreader_calendarbundle_day_date_day').val(curDate.getDate())
    }

    // init apps
    $(document).foundation();
    $('#calendar').fullCalendar({
        dayClick: function(date) {
            dayClick(date, this);
        },
        eventSources: [
            {
                url: '/day/events', // use the `url` property
                type: 'POST',
                data: function(){
                    return {
                        calendarId: location.toString().split('/').pop()
                    };
                },
                color: 'yellow',    // an option!
                textColor: 'black'  // an option!
            }
        ],
        color: 'yellow',
        textColor: 'black'
    });
    curDate = $('#calendar').fullCalendar('getDate');
    dayClick(curDate, $('.fc-today').first());
});
