function sortColumn(tbl, th, idx) {
  const tbody = tbl.querySelector('tbody');
  let rows = [];

  const asc = th.classList.contains('sortDesc');
  
  for (const tr of tbody.querySelectorAll('tr')) {
    rows.push(tr);
    tr.remove();
  }

  rows.sort((a, b) => {
    const av = a.querySelectorAll('td')[idx].innerText;
    const bv = b.querySelectorAll('td')[idx].innerText;

    if (asc) {
      return av.localeCompare(bv, 'en', {numeric: true});
    } else {
      return bv.localeCompare(av, 'en', {numeric: true});
    }
  });

  for (row of rows) {
    tbody.appendChild(row);
  }

  th.classList.remove('sortAsc');
  th.classList.remove('sortDesc');

  if (asc) {
    th.classList.add('sortAsc');
  } else {
    th.classList.add('sortDesc');
  }
}

function toggleSidebar() {
  const nav = document.querySelector('nav')
  const btn = document.getElementById('toggleSidebar')

  if (nav.classList.contains('shown')) {
    nav.classList.remove('shown')
    btn.innerHTML = '&raquo;'
  } else {
    nav.classList.add('shown')
    btn.innerHTML = '&laquo;'
  }
}

