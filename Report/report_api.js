// $reportURL = 'anotherpage.html?stock_symbol=' .$stock_symbol . '&start_date=' . $start_date 
// . '&end_date=' . $end_date . '&created_at=' . $created_at . '&creator_name=' . $creator_name . '&friend_name=' . $friend_name . '&notes=' . $notes;    

//TBD - dates functions for trading dates
// round function for interval

// Function to get URL parameters
function getURLParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);

}

const stock_symbol = getURLParameter('stock_symbol');

const start_date = getURLParameter('start_date');

const end_date = getURLParameter('end_date');

const created_at = getURLParameter('created_at');

const creator_name = getURLParameter('creator_name');

const notes = getURLParameter('notes');

const img_url = getURLParameter('img_url');

function getLastNonWeekendDate(inputDate) {
  // Clone the input date to avoid modifying the original
  let currentDate = new Date(inputDate);

  // Keep moving to the previous day until a non-weekend day is found
  while (currentDate.getDay() === 0 || currentDate.getDay() === 6) {
    currentDate.setDate(currentDate.getDate() - 1);
  }
  
  const year = currentDate.getFullYear();
  const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
  const day = currentDate.getDate().toString().padStart(2, '0');

  return `${year}-${month}-${day}`;

  return currentDate;
}

async function fetchStockData(stock_symbol) {
    
        
        const start_date_n = getLastNonWeekendDate(start_date);
        const end_date_n = getLastNonWeekendDate(end_date);
        console.log("start_date_n:",start_date_n);
        console.log("end_date_n:",end_date_n);
        console.log("start_date:",start_date);
        console.log("end_date:",end_date);


        
		const apiKey = 'HFtFizTW_Lvqn6Rjf3UgZeYE0czT5lHd'; // Replace with your actual API key
		const apiKey2 = 'YJxjBfJ1Pt8BVkUaewdgp3jZNfUuRKUw52jzS4ga'; // Replace with your actual API key
		const apiUrl1 = `https://api.polygon.io/v2/aggs/ticker/${stock_symbol}/range/1/day/${start_date_n}/${start_date_n}?adjusted=true&sort=asc&limit=120&apiKey=${apiKey}`;
        const apiUrl2 = `https://api.polygon.io/v2/aggs/ticker/${stock_symbol}/range/1/day/${end_date_n}/${end_date_n}?adjusted=true&sort=asc&limit=120&apiKey=${apiKey}`;


        console.log("apiUrl1:",apiUrl1);

	  try {
		const response1 = await fetch(apiUrl1);
		const data1 = await response1.json();
		
        const response2 = await fetch(apiUrl2);
		const data2 = await response2.json();



		// Process the data as needed
		let ticker_buy1 = data1.results[0].o.toFixed(2)
		let ticker_sell1 = data1.results[0].c.toFixed(2)
		let ticker_high1 = data1.results[0].h.toFixed(2)
		let ticker_low1 = data1.results[0].l.toFixed(2)
        let ticker_vol1 = data1.results[0].v.toFixed(2)
        


        		// Process the data as needed
		let ticker_buy2 = data2.results[0].o.toFixed(2)
		let ticker_sell2 = data2.results[0].c.toFixed(2)
		let ticker_high2 = data2.results[0].h.toFixed(2)
		let ticker_low2 = data2.results[0].l.toFixed(2)
        let ticker_vol2 = data2.results[0].v.toFixed(2)
        

		// accessing the span container 
        document.querySelector(".start_date").textContent = start_date; 
		document.querySelector(".ticker_buy1").textContent = ticker_buy1; 
		document.querySelector(".ticker_sell1").textContent = ticker_sell1; 
		document.querySelector(".ticker_high1").textContent = ticker_high1;
		document.querySelector(".ticker_low1").textContent = ticker_low1; 
        document.querySelector(".ticker_vol1").textContent = ticker_vol1; 


        // accessing the span container 
        document.querySelector(".end_date").textContent = end_date; 
		document.querySelector(".ticker_buy2").textContent = ticker_buy2; 
		document.querySelector(".ticker_sell2").textContent = ticker_sell2; 
		document.querySelector(".ticker_high2").textContent = ticker_high2;
		document.querySelector(".ticker_low2").textContent = ticker_low2;
        document.querySelector(".ticker_vol2").textContent = ticker_vol2; 


        // accessing the span container
        document.querySelector(".stock_symbol").textContent = stock_symbol;
        document.querySelector(".date_range").textContent = "Date Range: " + start_date + " - " + end_date;;
        document.querySelector(".creator_name").textContent = "Created by: " + creator_name;
        document.querySelector(".created_at").textContent = "Created at:: " + created_at;;
        document.querySelector(".notes").textContent = notes; 
        document.getElementById("chart").src = img_url;



	  } 
	  
	  catch (error) {
		console.error(`Error fetching data for ${stock_symbol}:`, error);
	  }
	  return 
}

fetchStockData(stock_symbol);