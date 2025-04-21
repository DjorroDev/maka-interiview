const length = 100;

function showMAKA(n) {
  for (let i = 1; i <= n; i++) {
    let output = "";
    if (i % 3 === 0) {
      output += "Mari";
    }

    if (i % 5 === 0) {
      output += " Berkarya";
    }

    output = output.trim();

    console.log(output || i);
  }
}

showMAKA(length);
