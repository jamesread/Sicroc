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
  const sidebar = document.getElementById('sidebar');

  if (sidebar.classList.contains('shown')) {
    setSidebarSticky(false);
    sidebar.classList.remove('shown');
  } else {
    sidebar.classList.add('shown');
  }
}

function toggleSidebarStuck() {
  const sidebar = document.getElementById('sidebar');

  if (sidebar.classList.contains('stuck')) {
    setSidebarSticky(false);
  } else {
    setSidebarSticky(true);
  }
}

function loadInitialSidebarState() {
  const state = window.localStorage.getItem('sidebarState');

  const sidebar = document.getElementById('sidebar');

  if (state === 'stuck') {
    setSidebarSticky(true);
  } else {
    setSidebarSticky(false);
  }

  if (window.innerWidth > 800 && sidebar.querySelectorAll('a').length > 0) {
    sidebar.classList.add('shown');
  }
}

function main() {
  loadInitialSidebarState();
}

function setSidebarSticky(setStuck) {
  const sidebar = document.getElementById('sidebar');

  const stickButton = document.getElementById('stick-icon');
  stickButton.innerHTML = '';

  const domIcon = document.createElement('iconify-icon');
  domIcon.setAttribute('width', '24');
  domIcon.setAttribute('height', '24');

  if (setStuck) {
    sidebar.classList.add('shown');
    sidebar.classList.add('stuck');
    domIcon.setAttribute('icon', 'mdi:pin');

    window.localStorage.setItem('sidebarState', 'stuck');
  } else {
    sidebar.classList.remove('stuck');
    domIcon.setAttribute('icon', 'mdi:pin-outline');

    window.localStorage.setItem('sidebarState', 'unstuck');
  }

  stickButton.appendChild(domIcon);
}
