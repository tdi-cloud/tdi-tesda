<div class="poppins-medium uppercase text-sky-900 dark:text-white text-sm col-span-2 py-2 px-5 flex items-center justify-between">

            <div class="text-md poppins-bold">
                <i class="fa-solid fa-graduation-cap"></i> EMPLOYEES Training Compliance Rate
            <span id="wait_trainings" class=" text-[10px] poppins-regular">Please wait...</span>
            </div>
                
            


            {{-- FILTER  --}}
            <div class=" flex items-center space-x-4">
                    
                    <select id="region_select"  class="select dark:bg-slate-600 dark:text-white text-sky-900 bg-white text-slate-900  text-black  poppins-medium  text-sm rounded-lg outline-none focus:outline-none cursor-pointer border-none"> 
                        <option value="ALL">All Regions</option> 
                        <option value="CO">Central Office</option> 
                        <option value="NCR">NCR</option> 
                        <option value="R1">Region I</option> 
                        <option value="R2">Region II</option> 
                        <option value="R3">Region III</option> 
                        <option value="R4A">Region IV-A</option> 
                        <option value="R4B">Region IV-B</option> 
                        <option value="R5">Region V</option> 
                        <option value="NIR">NIR</option> 
                        <option value="R6">Region VI</option> 
                        <option value="R7">Region VII</option> 
                        <option value="R8">Region VIII</option> 
                        <option value="R9">Region IX</option> 
                        <option value="R10">Region X</option> 
                        <option value="R11">Region XI</option> 
                        <option value="R12">Region XII</option> 
                        <option value="CAR">CAR</option> 
                        <option value="CARAGA">CARAGA</option> 
                    </select>

                    {{-- <div class="flex items-center border border-slate-300 rounded-2xl justify-between px-2">
                            SG
                        <select id="sg_condition" class="select border-none text-xs w-30 outline-none text-right">
                            <option class="text-right" value="=">Equal to</option>
                            <option value=">">Greater than</option>
                            <option value="<">Less than</option>
                        </select>

                        <input type="number" id="sg_value" value="18" class="input w-10 text-xs border-none outline-none ">


                    </div> --}}

                    


                   

                        <fieldset class="fieldset flex text-sky-900 dark:text-white">

                        {{-- <legend class="fieldset-legend text-slate-400 text-xs poppins-regular">PLANTILLA:</legend> --}}

                        <label class="label poppins-medium flex justify-between " >
                            <input type="checkbox" name="plant_status[]" value="PERMANENT"  checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                            PERMANENT
                        </label>
                        <label class="label  poppins-medium flex justify-between" >

                            <input type="checkbox" name="plant_status[]" value="JOB ORDER"  class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                            JOB ORDER
                        </label>
                        <label class="label  poppins-medium flex justify-between" >
                            
                            <input type="checkbox" name="plant_status[]" value="CONTRACTUAL" checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                            CONTRACTUAL
                        </label>
                        <label class="label  poppins-medium flex justify-between" >
                            
                            <input type="checkbox" name="plant_status[]" value="CTI" checked="checked" class="type_checkbox checkbox checkbox-warning checkbox-sm" />
                            CTI
                        </label>
                        
                        
                        </fieldset>

              


                    
                </div>

            {{-- FILTER  --}}





        </div>