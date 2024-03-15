

	async function fetchStockData() {


      
      var checkboxOne = sessionStorage.getItem('checkboxOne');
      var checkboxTwo = sessionStorage.getItem('checkboxTwo');
      var checkboxThree = sessionStorage.getItem('checkboxThree');
      var checkboxFour = sessionStorage.getItem('checkboxFour');
      var checkboxFive = sessionStorage.getItem('checkboxFive');
      var checkboxSix = sessionStorage.getItem('checkboxSix');
      var checkboxSeven = sessionStorage.getItem('checkboxSeven');
      var checkboxEight = sessionStorage.getItem('checkboxEight');
      var checkboxNine = sessionStorage.getItem('checkboxNine');
      var checkboxTen = sessionStorage.getItem('checkboxTen');
      var checkboxEleven = sessionStorage.getItem('checkboxEleven');
      var checkboxTwelve = sessionStorage.getItem('checkboxTwelve');
      var checkboxThirteen = sessionStorage.getItem('checkboxThirteen');
      var checkboxFourteen = sessionStorage.getItem('checkboxFourteen');
      
    var tikcer = 'SPY';

    if (checkboxOne == 'ETFs'){
        var tikcer = 'SPY';
    }
    if (checkboxTwo == 'Day Trading'){
        var tikcer = 'TSLA';
    }
    if (checkboxThree == 'Tech Stocks'){
        var tikcer = 'AAPL';
    }
    if (checkboxFour == 'Crypto'){
        var tikcer = 'MSTR';
    }
    if (checkboxFive == 'Bonds'){
        var tikcer = 'TLT';
    }
    if (checkboxSix == 'S&P 500'){
        var tikcer = 'SPY';
    }
    if (checkboxSix == 'Growth Stocks'){
        var tikcer = 'AMZN';
    }
    if (checkboxSeven == 'REITs'){
        var tikcer = 'SPG';
    }
    if (checkboxEight == 'Stock Options'){
        var tikcer = 'NFLX';
    }
    if (checkboxNine == 'Energy stocks'){
        var tikcer = 'XOM';
    }
    if (checkboxTen == 'Healthcare Stocks'){
        var tikcer = 'JNJ';
    }
    if (checkboxEleven == 'Clover'){
        var tikcer = 'CLOV';
    }
    if (checkboxTwelve == 'Algorithmic Trading'){
        var tikcer = 'GS';
    }
    if (checkboxFourteen == 'JPX'){
        var tikcer = 'JPM';
    }
    

    const apiKey = 'HFtFizTW_Lvqn6Rjf3UgZeYE0czT5lHd'; // Replace with your actual API key
	const apiUrl = `https://api.polygon.io/v2/reference/news?ticker=${tikcer}&limit=6&apiKey=${apiKey}`;
	console.log(apiUrl)
	
	  try {
		const response = await fetch(apiUrl);
		const data = await response.json();

		
		// Process the data as needed
		let title1 = data.results[0].title
		let description1 = data.results[0].description//.substring(0,300) + '..'
		let title2 = data.results[1].title
		let description2 = data.results[1].description//.substring(0,300) + '..'
		let title3 = data.results[2].title
		let description3 = data.results[2].description//.substring(0,300) + '..'
		let title4 = data.results[3].title
		let description4 = data.results[3].description//.substring(0,300) + '..'
		let title5 = data.results[4].title
		let description5 = data.results[4].description//.substring(0,300) + '..'
		let title6 = data.results[5].title
		let description6 = data.results[5].description//.substring(0,300) + '..'


		console.log(description1)
		if (description1 == null) {
            description1 = title1
        }

		if (description2 == null) {
            description2 = title2
        }

		if (description3 == null) {
            description3 = title3
        }

		if (description4 == null) {
            description4 = title4
        }

		if (description5 == null) {
            description5 = title5
        }

		if (description6 == null) {
            description6 = title6
        }

		if (description1.length > 300) {
            description1 = description1.substring(0, 300) + '...';
        }
		
		if (description2.length > 300) {
            description2 = description2.substring(0, 300) + '...';
        }
		
		if (description3.length > 300) {
            description3 = description3.substring(0, 300) + '...';
        }

		if (description4.length > 300) {
            description4 = description4.substring(0, 300) + '...';
        }
		if (description5.length > 300) {
            description5 = description5.substring(0, 300) + '...';
        }
		if (description6.length > 300) {
            description6 = description6.substring(0, 300) + '...';
        }
	
		console.log(description2)
		console.log(description1)
	
		// accessing the span container 
		document.getElementById("title1").innerHTML = title1;
		document.getElementById("description1").innerHTML = description1;

		document.getElementById("title2").innerHTML = title2;
		document.getElementById("description2").innerHTML = description2;

		document.getElementById("title3").innerHTML = title3;
		document.getElementById("description3").innerHTML = description3;

		document.getElementById("title4").innerHTML = title4;
		document.getElementById("description4").innerHTML = description4;

		document.getElementById("title5").innerHTML = title5;
		document.getElementById("description5").innerHTML = description5;

		document.getElementById("title6").innerHTML = title6;
		document.getElementById("description6").innerHTML = description6;





	  } 
	  
	  catch (error) {
		console.error(`Error fetching data for `, error);
	  }
	  return 
	}
	

	// Calling the function 
	fetchStockData(); 
	  
