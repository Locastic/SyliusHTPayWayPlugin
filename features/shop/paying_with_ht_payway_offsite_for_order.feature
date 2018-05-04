@paying_with_ht_payway_offsite_for_order
Feature: Paying with Mollie during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with Mollie

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "john@locastic.com" identified by "password123"
        And the store has a payment method "HT PayWay Offsite" with a code "ht_payway_offsite" and HT PayWay Offsite payment gateway
        And the store has a product "PHP T-Shirt" priced at "€19.99"
        And the store ships everywhere for free
        And I am logged in as "john@locastic.com"

    @ui @javascript
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "HT PayWay Offsite" payment method
        When I confirm my order with HT PayWay Offsite payment
#        And I sign in to HT PayWay Offsite and pay successfully
#        Then I should be notified that my payment has been completed