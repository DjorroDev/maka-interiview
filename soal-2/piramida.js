const length = 1;

function reversePyramid(n) {
  for (let i = n; i >= 1; i--) {
    let output = "";

    for (let k = i; k <= n - 1; k++) {
      output += " ";
    }

    for (let j = 0; j < i * 2 - 1; j++) {
      //   console.log("*");
      output += "*";
    }

    console.log(output);
  }
}

reversePyramid(length);
