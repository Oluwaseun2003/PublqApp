'use strict';

const chartOne = document.getElementById('incomeChart').getContext('2d');
const myIncomeChart = new Chart(chartOne, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Income',
      data: incomeArr,
      borderColor: '#1d7af3',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#1d7af3',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#1d7af3'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    }
  }
});

const chartTwo = document.getElementById('TotalEventBookingChart').getContext('2d');
const myEventBookingChart = new Chart(chartTwo, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Event Bookings',
      data: totalBookings,
      borderColor: '#6861CE ',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#6861CE ',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#6861CE'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          stepSize: 1
        }
      }]
    }
  }
});



const chartThree = document.getElementById('ProductOrderChart').getContext('2d');
const ProductOrderChart = new Chart(chartThree, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Income',
      data: productIncome,
      borderColor: '#01a100',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#01a100',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#01a100'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    }
  }
});

const chartFour = document.getElementById('TotalProductOrderChart').getContext('2d');
const TotalProductOrderChart = new Chart(chartFour, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Product Order',
      data: totalOders,
      borderColor: '#fcbd00',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#fcbd00',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#fcbd00'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          stepSize: 1
        }
      }]
    }
  }
});
