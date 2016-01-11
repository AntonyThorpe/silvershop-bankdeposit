<%--  Bank Deposit Needed Statement for top of Order/Invoice --%>
<div class="row">
	<div class="col-sm-12 col-md-12">
	 	<% _t("IntroductionMessageStart","Please deposit ") %>
	 		<strong>$TotalOutstanding.Nice</strong>
	 	<% _t("IntroductionMessageContinueFirstLine"," into:") %>
	 	<br>
	 	
	 	<% _t("BankAccountNumberTitle", "Bank Account Number: ") %>
	 	<% with SiteConfig %>
	 			<strong>$BankAccountNumber</strong>
	 	<% end_with %>
	 	<br>
	 	<% _t("DepositReference", "Reference: ") %>
	 		<strong>$Reference</strong>
	 	<br>
	 	<% _t("DepositCode", "Code: ") %>
	 		<strong><% _t("YourName","Your name") %></strong>
	 	<br>
	 	</div>					
</div>
