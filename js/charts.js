// myCharts.js

// Helper: create an SVG element with the proper namespace.
function createSVG(width, height) {
  const svgNS = "http://www.w3.org/2000/svg";
  const svg = document.createElementNS(svgNS, "svg");
  svg.setAttribute("width", width);
  svg.setAttribute("height", height);
  svg.style.border = "1px solid #ddd";
  return svg;
}

// Helper: create a tooltip element (hidden by default)
function createTooltip() {
  const tooltip = document.createElement("div");
  tooltip.style.position = "absolute";
  tooltip.style.background = "rgba(0,0,0,0.7)";
  tooltip.style.color = "#fff";
  tooltip.style.padding = "5px 10px";
  tooltip.style.borderRadius = "4px";
  tooltip.style.pointerEvents = "none";
  tooltip.style.fontSize = "12px";
  tooltip.style.display = "none";
  document.body.appendChild(tooltip);
  return tooltip;
}

const tooltip = createTooltip();

// Chart Library Object
const MyCharts = {
  // Create an interactive line chart
  createLineChart: function (data, config = {}) {
  // Configuration
  const width = config.width || 600;
  const height = config.height || 400;
  // Increase bottom and left margins to allow space for axis labels
  const margin = config.margin || { top: 20, right: 20, bottom: 50, left: 50 };
  const stroke = config.stroke || "steelblue";
  const strokeWidth = config.strokeWidth || 2;
  const circleRadius = config.circleRadius || 4;
  
  const svg = createSVG(width, height);
  const plotWidth = width - margin.left - margin.right;
  const plotHeight = height - margin.top - margin.bottom;
  const svgNS = "http://www.w3.org/2000/svg";
  
  // Determine scales
  // x values are timestamps; y values start at 0
  const xMin = Math.min(...data.map(d => d.x));
  const xMax = Math.max(...data.map(d => d.x));
  const yMin = 0;
  const yMax = Math.max(...data.map(d => d.y));
  
  // Scale functions (linear)
  const scaleX = d => margin.left + ((d - xMin) / (xMax - xMin)) * plotWidth;
  const scaleY = d => margin.top + plotHeight - ((d - yMin) / (yMax - yMin)) * plotHeight;
  
  // Draw horizontal gridlines and y-axis labels
  const numYTicks = 5;
  for (let i = 0; i <= numYTicks; i++) {
    const tickValue = yMin + (i * (yMax - yMin)) / numYTicks;
    const y = scaleY(tickValue);
    
    // Horizontal gridline
    const hLine = document.createElementNS(svgNS, "line");
    hLine.setAttribute("x1", margin.left);
    hLine.setAttribute("y1", y);
    hLine.setAttribute("x2", margin.left + plotWidth);
    hLine.setAttribute("y2", y);
    hLine.setAttribute("stroke", "#ccc");
    hLine.setAttribute("stroke-dasharray", "4 2");
    svg.appendChild(hLine);
    
    // Y-axis label
    const text = document.createElementNS(svgNS, "text");
    text.setAttribute("x", margin.left - 10);
    text.setAttribute("y", y + 4); // Adjust vertically to center the text
    text.setAttribute("text-anchor", "end");
    text.setAttribute("font-size", "12px");
    text.textContent = tickValue.toFixed(0);
    svg.appendChild(text);
  }
  
  // Draw vertical gridlines and x-axis labels
  // Here we assume each data point corresponds to a month (data is sorted)
  const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
  data.forEach(d => {
    const x = scaleX(d.x);
    // Vertical gridline
    const vLine = document.createElementNS(svgNS, "line");
    vLine.setAttribute("x1", x);
    vLine.setAttribute("y1", margin.top);
    vLine.setAttribute("x2", x);
    vLine.setAttribute("y2", margin.top + plotHeight);
    vLine.setAttribute("stroke", "#ccc");
    vLine.setAttribute("stroke-dasharray", "4 2");
    svg.appendChild(vLine);
    
    // X-axis label: convert timestamp to month-year format
    const date = new Date(d.x);
    const label = monthNames[date.getMonth()] + " " + date.getFullYear();
    const text = document.createElementNS(svgNS, "text");
    text.setAttribute("x", x);
    text.setAttribute("y", margin.top + plotHeight + 20);
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("font-size", "12px");
    text.textContent = label;
    svg.appendChild(text);
  });
  
  // Draw the line path
  const pathData = data.map((d, i) => {
    const prefix = i === 0 ? "M" : "L";
    return `${prefix} ${scaleX(d.x)} ${scaleY(d.y)}`;
  }).join(" ");
  
  const path = document.createElementNS(svgNS, "path");
  path.setAttribute("d", pathData);
  path.setAttribute("fill", "none");
  path.setAttribute("stroke", stroke);
  path.setAttribute("stroke-width", strokeWidth);
  svg.appendChild(path);
  
  // Draw data points and add labels with the value on top of each dot
  data.forEach(d => {
    const cx = scaleX(d.x);
    const cy = scaleY(d.y);
    
    // Draw the dot
    const circle = document.createElementNS(svgNS, "circle");
    circle.setAttribute("cx", cx);
    circle.setAttribute("cy", cy);
    circle.setAttribute("r", circleRadius);
    circle.setAttribute("fill", stroke);
    circle.style.cursor = "pointer";
    svg.appendChild(circle);
    
    // Label the dot with its y value
    const labelText = document.createElementNS(svgNS, "text");
    labelText.setAttribute("x", cx);
    labelText.setAttribute("y", cy - circleRadius - 5);
    labelText.setAttribute("text-anchor", "middle");
    labelText.setAttribute("font-size", "12px");
    labelText.setAttribute("fill", "#000");
    labelText.textContent = d.y.toFixed(2);
    svg.appendChild(labelText);
    
    // Optional tooltip events for additional interactivity
    circle.addEventListener("mouseover", (e) => {
      tooltip.innerHTML = `x: ${new Date(d.x).toLocaleDateString()}<br>y: ${d.y}`;
      tooltip.style.display = "block";
    });
    circle.addEventListener("mousemove", (e) => {
      tooltip.style.left = e.pageX + 10 + "px";
      tooltip.style.top = e.pageY + 10 + "px";
    });
    circle.addEventListener("mouseout", () => {
      tooltip.style.display = "none";
    });
  });
  
  return svg;
}
,

  createBarChart: function (data, config = {}) {
  const width = config.width || 600;
  const height = config.height || 400;
  const margin = config.margin || { top: 20, right: 20, bottom: 40, left: 40 };
  const barColor = config.barColor || "purple";

  const svg = createSVG(width, height);
  const plotWidth = width - margin.left - margin.right;
  const plotHeight = height - margin.top - margin.bottom;
  const svgNS = "http://www.w3.org/2000/svg";

  // Calculate bar dimensions
  const barWidth = plotWidth / data.length * 0.8;
  const barGap = (plotWidth / data.length) * 0.2;
  const maxValue = Math.max(...data.map(d => d.value));

  // Draw horizontal gridlines (for y-axis ticks)
  const numYTicks = 5;
  for (let i = 0; i <= numYTicks; i++) {
    const tickValue = (i * maxValue) / numYTicks;
    const y = margin.top + plotHeight - (tickValue / maxValue) * plotHeight;
    const line = document.createElementNS(svgNS, "line");
    line.setAttribute("x1", margin.left);
    line.setAttribute("y1", y);
    line.setAttribute("x2", margin.left + plotWidth);
    line.setAttribute("y2", y);
    line.setAttribute("stroke", "#ccc");
    line.setAttribute("stroke-dasharray", "4 2");
    svg.appendChild(line);
  }

  // Draw bars
  data.forEach((d, i) => {
    const x = margin.left + i * (barWidth + barGap) + barGap / 2;
    const barHeight = (d.value / maxValue) * plotHeight;
    const y = margin.top + plotHeight - barHeight;
    const rect = document.createElementNS(svgNS, "rect");
    rect.setAttribute("x", x);
    rect.setAttribute("y", y);
    rect.setAttribute("width", barWidth);
    rect.setAttribute("height", barHeight);
    rect.setAttribute("fill", barColor);
    rect.style.cursor = "pointer";
    rect.addEventListener("mouseover", (e) => {
      tooltip.innerHTML = `${d.label}: ${d.value}`;
      tooltip.style.display = "block";
    });
    rect.addEventListener("mousemove", (e) => {
      tooltip.style.left = e.pageX + 10 + "px";
      tooltip.style.top = e.pageY + 10 + "px";
    });
    rect.addEventListener("mouseout", () => {
      tooltip.style.display = "none";
    });
    svg.appendChild(rect);

    // Add label below each bar
    const text = document.createElementNS(svgNS, "text");
    text.setAttribute("x", x + barWidth / 2);
    text.setAttribute("y", margin.top + plotHeight + 15);
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("font-size", "12px");
    text.textContent = d.label;
    svg.appendChild(text);
  });

  return svg;
},

  // Create an interactive pie chart
  createPieChart: function (data, config = {}) {
  const width = 600;
  const height = config.height || 400;
  const colors = config.colors || ["#4daf4a", "#377eb8", "#ff7f00", "#984ea3", "#e41a1c"];
  const radius = Math.min(width, height) / 2;
  const svg = createSVG(width, height);
  const svgNS = "http://www.w3.org/2000/svg";
  const centerX = width / 2;
  const centerY = height / 2;
  
  const total = data.reduce((acc, d) => acc + d.value, 0);
  let startAngle = 0;
  
  data.forEach((d, i) => {
    const sliceAngle = (d.value / total) * Math.PI * 2;
    const endAngle = startAngle + sliceAngle;
    
    // Calculate path for the slice
    const x1 = centerX + radius * Math.cos(startAngle);
    const y1 = centerY + radius * Math.sin(startAngle);
    const x2 = centerX + radius * Math.cos(endAngle);
    const y2 = centerY + radius * Math.sin(endAngle);
    const largeArcFlag = sliceAngle > Math.PI ? 1 : 0;
    
    const pathData = [
      `M ${centerX} ${centerY}`,
      `L ${x1} ${y1}`,
      `A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2}`,
      "Z"
    ].join(" ");
    
    const path = document.createElementNS(svgNS, "path");
    path.setAttribute("d", pathData);
    path.setAttribute("fill", colors[i % colors.length]);
    path.style.cursor = "pointer";
    
    // Tooltip events for the slice
    path.addEventListener("mouseover", (e) => {
      tooltip.innerHTML = `${d.label}: ${d.value}`;
      tooltip.style.display = "block";
    });
    path.addEventListener("mousemove", (e) => {
      tooltip.style.left = e.pageX + 10 + "px";
      tooltip.style.top = e.pageY + 10 + "px";
    });
    path.addEventListener("mouseout", () => {
      tooltip.style.display = "none";
    });
    svg.appendChild(path);
    
    // Calculate the middle angle for label positioning
    const midAngle = startAngle + sliceAngle / 2;
    const labelRadius = radius * 0.7; // adjust for inside placement
    const labelX = centerX + labelRadius * Math.cos(midAngle);
    const labelY = centerY + labelRadius * Math.sin(midAngle);
    const percentage = ((d.value / total) * 100).toFixed(1);
    const text = document.createElementNS(svgNS, "text");
    text.setAttribute("x", labelX);
    text.setAttribute("y", labelY);
    text.setAttribute("text-anchor", "middle");
    text.setAttribute("font-size", "12px");
    text.setAttribute("fill", "#fff");
    text.textContent = `${d.label}: ${d.value} (${percentage}%)`;
    svg.appendChild(text);
    
    startAngle = endAngle;
  });
  
  return svg;
}
};

