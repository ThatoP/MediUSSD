public JSONObject ProcessRequest(String request) throws MalformedURLException {
        JSONObject jsonresponse = null;
        jsonresponse = new JSONObject();
        props = new Props();
        logging = new Logging(props.getLogsPath());
        dbfuctions = new DBFunctions(logging, props);
        //String pin = "";
        //String requestedpin = "";
        boolean isvalid = isJSONValid(request);
        
        if (isvalid == false) {
            jsonresponse.put("Responsecode", "99");
            jsonresponse.put("Message", "Invalid Request/Response Detected!");
        } else {
            JSONObject jsonrequest = new JSONObject(request);
            String txtcode = jsonrequest.getString("processingcode");
            //Log request to a file
            //System.out.println(jsonrequest);

            if (txtcode.equals("101010")) {//Retrieve patient details
               String ph = jsonrequest.getString("phoneNumber"); //get medicalId from the incoming json request
			   //select statement to get patient's details
			   String Query = "select * from paramedics where phone = '"+ ph +"'";
               
			   //invoke your database functions class to execute the query 
			   JSONObject resp = dbfuctions.methodname(Query);
                jsonresponse = resp;
			      
            } else if (txtcode.equals("101011")) {//what you want to perform