/**
* 2014-2015 (c) Axalone France - Express-Mailing
*
* This file is a commercial module for Prestashop
* Do not edit or add to this file if you wish to upgrade PrestaShop or
* customize PrestaShop for your needs please refer to
* http://www.express-mailing.com for more information.
*
* @author    Axalone France <info@express-mailing.com>
* @copyright 2014-2015 (c) Axalone France
* @license   http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
*/

// Graph des aboutis/npai sur 24 heures

function myData()
{
	return [
		{
			key: "Aboutis",
			values: series1,
			color: "green"
		}
	];
}

$(function ()
{
    nv.addGraph(function()
	{
		var chart = nv.models.multiBarChart()
			.x(function(d) { return d.x; })
			.y(function(d) { return d.y; })
			.showLegend(true)
			.tooltips(true)
			.showControls(false)		// Allow user to switch between 'Grouped' and 'Stacked' mode.
			.reduceXTicks(true)			// If 'false', every single x-axis tick label will be rendered.
			.showXAxis(true)
			.showYAxis(true);

		chart.xAxis
			.tickFormat(function(d){return d3.time.format('%e %b - %Hh%M')(new Date(d));})
			.showMaxMin(true);

		chart.yAxis
			.tickFormat(d3.format(',f'))
			.showMaxMin(true);

		d3.select('#stack')
			.datum(myData)
			.call(chart);

        nv.utils.windowResize(chart.update);
        return chart;
	});

});