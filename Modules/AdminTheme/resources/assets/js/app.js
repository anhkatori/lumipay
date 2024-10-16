import './overlayscrollbars';
import $ from 'jquery';
window.$ = window.jQuery = $;
import 'bootstrap';
import 'admin-lte';

import('jquery-ui/ui/widgets/datepicker.js').then(() => {
	$( function() {
	    var startDate;
	    var endDate;
	    
	    var selectCurrentWeek = function() {
	        window.setTimeout(function () {
	            $('#time-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
	        }, 1);
	    }
	    
	    $('#time-picker').datepicker( {
	    	changeMonth: true,
     		changeYear: true,
	        showOtherMonths: true,
	        selectOtherMonths: true,
	        onSelect: function(dateText, inst) { 
	            var date = $(this).datepicker('getDate');
	            startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay());
	            endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
	            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;

	            $('#startDate').text($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
	            $('#endDate').text($.datepicker.formatDate( dateFormat, endDate, inst.settings ));

	            $('[name="startDate"]').val($.datepicker.formatDate('dd-mm-yy', startDate));

	            $('[name="endDate"]').val($.datepicker.formatDate('dd-mm-yy', endDate));

	            $('[name="month"]').val($.datepicker.formatDate('mm-yy', date));
	            $('#month').text($.datepicker.formatDate('mm-yy', date));
	            
	            $('[name="year"]').val($.datepicker.formatDate('yy', date));
	            $('#year').text($.datepicker.formatDate('yy', date));

	            
	            selectCurrentWeek();
	            window.updateChartsAjax();

	        },
	        dateFormat: 'dd-mm-yy',
	        beforeShowDay: function(date) {
	            var cssClass = '';
	            if(date >= startDate && date <= endDate)
	                cssClass = 'ui-datepicker-current-day';
	            return [true, cssClass];
	        },
	        onChangeMonthYear: function(year, month, inst) {
	            selectCurrentWeek();
	        }
	    });
	    
	    $('#time-picker .ui-datepicker-calendar tr').on('mousemove', function() { $(this).find('td a').addClass('ui-state-hover'); });
	    $('#time-picker .ui-datepicker-calendar tr').on('mouseleave', function() { $(this).find('td a').removeClass('ui-state-hover'); });



	    
  	});
});

