// from https://stackoverflow.com/a/1199420/7013346
// usage
// truncate.apply(s, [11, true]);
export default function truncate(n, useWordBoundary) {
  if (this.length <= n) { return this; }
  var subString = this.substr(0, n-1);
  return (useWordBoundary 
     ? subString.substr(0, subString.lastIndexOf(' ')) 
     : subString) + "&hellip";
}
