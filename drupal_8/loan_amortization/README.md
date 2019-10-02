The "Loan amortization" module is designed to calculate payments for loan repayments. 

There is a form for this purpose by path: /loan_amortization

You have a possibility to enter data by the form or/and enter values in URL, using GET parameters*.

For configuring default values refill a form on page: /admin/structure/loan_amortization/settings_values
For configuring the labels for English and other languages you can fill or refill a form on page: /admin/structure/loan_amortization/settings_labels

Rest API
To get "Loan Summary" data from Rest API use POST method by path /api/loan-summary and pass 'Entered data' in a body of json format. For instance:
{
  "loan_amount": "1000",
  "annual_rate": "5",
  "loan_period": "1",
  "annual_payments": "5",
  "start_of_loan": "6/12/19",
  "extra_payment": "50"
}

* Use these variables for write your GET parameters:
  loan_amount - for 'Loan Amount'
  annual_rate - for 'Annual Interest Rate'
  loan_period - for 'Loan Period in Year'
  annual_payments for 'Number of Payments Per Year'
  start_of_loan - for 'Start Date of Loan'
  extra_payment - 'Extra Payment'