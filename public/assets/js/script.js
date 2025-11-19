// public/assets/js/scripts.js
document.addEventListener('DOMContentLoaded', function(){
  // menu toggle for small screens
  const menuToggle = document.getElementById('menu-toggle');
  const sidebar = document.querySelector('.sidebar');
  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
      document.querySelector('.content').classList.toggle('expanded');
    });
  }

  // styled radio selection - add active class when checked (delegation)
  document.querySelectorAll('.radio-grid .option input').forEach(function(inp){
    inp.addEventListener('change', function(){
      document.querySelectorAll('.radio-grid .option').forEach(o=>o.classList.remove('selected'));
      if (this.checked) this.closest('.option').classList.add('selected');
    });
  });
});
