<script>
  const text = "Connect Minds. Share Ideas."; // The text to display
  const h1 = document.getElementById("typing"); // Target the h1 element
  let index = 0;

  function typeCharacter() {
    if (index < text.length) {
      h1.textContent += text.charAt(index);// Add one character at a time
      index++;
      setTimeout(typeCharacter, 100); // Adjust speed by changing the timeout
    }
}

  // Start the typing animation when the page loads
  window.onload = typeCharacter;
</script>
