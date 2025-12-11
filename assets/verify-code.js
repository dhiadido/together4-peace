document.addEventListener('DOMContentLoaded', function () {
  const inputs = document.querySelectorAll('.code-input');
  const codeForm = document.getElementById('codeForm');
  const fullCode = document.getElementById('fullCode');

  inputs.forEach((input, index) => {
    input.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9]/g, '');

      if (this.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Backspace' && this.value === '' && index > 0) {
        inputs[index - 1].focus();
      }
    });
  });

  if (codeForm && fullCode) {
    codeForm.addEventListener('submit', function () {
      let code = '';
      inputs.forEach((input) => {
        code += input.value;
      });
      fullCode.value = code;
    });
  }
});

