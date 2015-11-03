<bubbles-pagination-week>
  <div class="row">
    <nav class="text-center">
      <ul class="pagination">
        <li>
          <a href="#week/{ moment(opts.date.date).subtract(1, 'month').format('DD-MM-YYYY') }" title="Released as of { moment(opts.date.date).subtract(1, 'month').format('MMM Do, YYYY') }">-month</a>
        </li>
        <li>
          <a href="#week/{ moment(opts.date.date).subtract(1, 'week').format('DD-MM-YYYY') }" title="Released as of { moment(opts.date.date).subtract(1, 'week').format('MMM Do, YYYY') }">-week</a>
        </li>
        <li>
          <a href="#week/{ moment(opts.date.date).add(1, 'week').format('DD-MM-YYYY') }" title="Released as of { moment(opts.date.date).add(1, 'week').format('MMM Do, YYYY') }">+week</a>
        </li>
        <li>
          <a href="#week/{ moment(opts.date.date).add(1, 'month').format('DD-MM-YYYY') }" title="Released as of { moment(opts.date.date).add(1, 'month').format('MMM Do, YYYY') }">+month</a>
        </li>
      </ul>
    </nav>
  </div>
</bubbles-pagination-week>
