@paying_with_ht_payway_offsite_for_order
Feature: Paying with HT PayWay Offsite during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with HT PayWay Offsite

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "john@locastic.com" identified by "password123"
        And the store has a payment method "HT PayWay Offsite" with a code "ht_payway_offsite" and HT PayWay Offsite payment gateway
        And the store has a product "PHP T-Shirt" priced at "€0.99"
        And the store ships everywhere for free
        And I am logged in as "john@locastic.com"

    @ui
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "HT PayWay Offsite" payment method
        When I confirm my order with HT PayWay Offsite payment
        And I payed successfully in to HT PayWay Offsite gateway
        Then I should be notified that my payment has been completed
        And I should see the thank you page

    @ui
    Scenario: Cancelling the payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "HT PayWay Offsite" payment method
        When I confirm my order with HT PayWay Offsite payment
        And I cancel my HT PayWay Offsite payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again

    @ui
    Scenario: Retrying the payment with success
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "HT PayWay Offsite" payment method
        When I confirm my order with HT PayWay Offsite payment
        And I cancel my HT PayWay Offsite payment
        When I try to pay again
        And I payed successfully in to HT PayWay Offsite gateway
        Then I should be notified that my payment has been completed
        And I should see the thank you page

    @ui
    Scenario: Retrying the payment and failing
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "HT PayWay Offsite" payment method
        When I confirm my order with HT PayWay Offsite payment
        And I cancel my HT PayWay Offsite payment
        When I try to pay again
        And I cancel my HT PayWay Offsite payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again