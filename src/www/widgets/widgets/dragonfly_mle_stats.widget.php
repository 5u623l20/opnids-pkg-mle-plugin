<?php

/*
Copyright (C) 2014-2016 Deciso B.V.
Copyright (C) 2007 Scott Dale
Copyright (C) 2004-2005 T. Lechat <dev@lechat.org>
Copyright (C) 2004-2005 Manuel Kasper <mk@neon1.net>
Copyright (C) 2004-2005 Jonathan Watt <jwatt@jwatt.org>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice,
this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright
notice, this list of conditions and the following disclaimer in the
documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY
AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
AUTHOR BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
POSSIBILITY OF SUCH DAMAGE.
 */

require_once "guiconfig.inc";
?>

<table class="table" data-plugin="mle" data-callback="mle_widget_update">
  <tbody>
    <tr>
      <td>
        <div id="mle_stats_chart">
          <svg style="height:250px;"></svg>
        </div>
      </td>
    </tr>
  </tbody>
</table>

<script src="/ui/js/moment-with-locales.min.js?v=fa7058bdbd070415"></script>
<script>
var chart, selection = d3.select('#mle_stats_chart svg');
nv.addGraph(function() {
    chart = nv.models.lineChart()
        .x(function(d) { return d[0] })
        .y(function(d) { return d[1] })
        .color(d3.scale.category10().range())
        .useInteractiveGuideline(true)

    // X-AXIS
    chart.xAxis.axisLabel('Time').tickFormat(function(d) {
        var date = new Date(0);
        date.setUTCSeconds(d);
        return d3.time.format('%b %e %H:%M:%S')(date);
    });

    // Y-AXIS
    chart.yAxis
        .axisLabel('Events (per second)')
        .tickFormat(d3.format('.0'));
});

function mle_widget_update(sender, data, max_measures) {
    try {
        data = JSON.parse(window.atob(data))
    } catch(e) {
        console.error(e);
    }

    if (data) {
        const d = formatData(data);
        if (d) {
            chart.forceY([0, d3.max(d.y) + 10000]);
            selection = d3.select('#mle_stats_chart svg')
                .datum(d.datum)
                .transition()
                .duration(500)
                .call(chart);
        } else {
            d3.select('#mle_stats_chart svg').datum([]).call(chart.noData('No data available.'));
        }
    }
}

function formatData(data) {
    let y = [];

    const datum = data.reduce(function(s, item) {
        const operations = Object.keys(item.operations);

        operations.map(function(k, index) {
            y.push(item.operations[k]);
            if (!s.length || s.length < operations.length) {
                s.push({
                    key: k,
                    values: [[moment(item.time).format('X'), item.operations[k]]],
                });
            } else {
                s[index].values.push([moment(item.time).format('X'), item.operations[k]]);
            }
        });
        return s;
    }, []);

    return { datum, y };
}
</script>
