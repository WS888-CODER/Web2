function showForm(role) {
  document.getElementById('patient-form').classList.add('hidden');
  document.getElementById('doctor-form').classList.add('hidden');
  
  if (role === 'patient') {
    document.getElementById('patient-form').classList.remove('hidden');
  } else if (role === 'doctor') {
    document.getElementById('doctor-form').classList.remove('hidden');
  }
}

function redirectToPage(url) {
  window.location.href = url;
}
